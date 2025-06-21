<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Get common appointment statistics for all views
     * 
     * @return array
     */
    private function getAppointmentStats()
    {
        return [
            'totalAppointments' => Appointment::count(),
            'scheduledAppointments' => Appointment::where('status', 'scheduled')->count(),
            'completedAppointments' => Appointment::where('status', 'completed')->count(),
            'cancelledAppointments' => Appointment::where('status', 'cancelled')->count(),
        ];
    }

    /**
     * Display a listing of appointments.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'service', 'payment', 'availableTime'])
            ->where(function($query) {
                $query->whereHas('payment', function($q) {
                    $q->where('status', \App\Models\Payment::STATUS_COMPLETED);
                })
                ->orWhereHas('payment', function($q) {
                    $q->where('payment_method', \App\Models\Payment::METHOD_CASH);
                });
            });
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('appointment_id', 'like', "%{$search}%");
        }
        
        // Get appointments with pagination - Changed from 10 to 3
        $appointments = $query->orderBy('created_at', 'desc')->paginate(3);
        
        // Get appointment statistics
        $stats = $this->getAppointmentStats();
        
        return view('admin.appointments.index', array_merge(
            compact('appointments'),
            $stats
        ));
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $stats = $this->getAppointmentStats();
        $customers = User::where('role', 'customer')->get();
        $services = Service::where('active', true)->get();
        
        return view('admin.appointments.create', array_merge(
            compact('customers', 'services'),
            $stats
        ));
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,service_id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'available_time_id' => 'required|exists:available_times,available_time_id',
            'notes' => 'nullable|string',
        ]);
        
        // Check if the time slot is unavailable for the selected date
        $isUnavailable = \App\Models\UnavailableTime::where('date', $validated['appointment_date'])
            ->where('available_time_id', $validated['available_time_id'])
            ->exists();
        
        if ($isUnavailable) {
            return back()->withErrors(['available_time_id' => 'This time slot is not available for the selected date.'])->withInput();
        }
        
        // Create appointment
        $appointment = Appointment::create([
            'user_id' => $validated['user_id'],
            'service_id' => $validated['service_id'],
            'available_time_id' => $validated['available_time_id'],
            'appointment_date' => $validated['appointment_date'], // Store as date only
            'status' => 'scheduled',
            'notes' => $validated['notes'],
        ]);
        
        // Create a pending payment record
        \App\Models\Payment::create([
            'user_id' => $validated['user_id'],
            'appointment_id' => $appointment->appointment_id,
            'amount' => 0, // Set appropriate amount based on service
            'status' => \App\Models\Payment::STATUS_PENDING,
            'payment_method' => \App\Models\Payment::METHOD_CASH, // Default method
            'transaction_id' => 'APP' . time() . rand(1000, 9999), // Generate a unique transaction ID
            'payment_date' => now(), // Also need to set payment_date as it's required
        ]);
        
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully');
    }

    /**
     * Display the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $appointment = Appointment::with(['user', 'service', 'payment', 'availableTime'])
            ->findOrFail($id);
        
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $stats = $this->getAppointmentStats();
        $customers = User::where('role', 'customer')->get();
        $services = Service::where('active', true)->get();
        
        return view('admin.appointments.edit', array_merge(
            compact('appointment', 'customers', 'services'),
            $stats
        ));
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,service_id',
            'appointment_date' => 'required|date',
            'available_time_id' => 'required|exists:available_times,available_time_id',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);
        
        // Get the available time details
        $availableTime = \App\Models\AvailableTime::findOrFail($validated['available_time_id']);
        
        $appointment->update([
            'user_id' => $validated['user_id'],
            'service_id' => $validated['service_id'],
            'available_time_id' => $validated['available_time_id'],
            'appointment_date' => $validated['appointment_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);
        
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully');
    }

    /**
     * Update the status of the specified appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $appointment->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $appointment->notes,
        ]);
        
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment status updated successfully');
    }

    /**
     * Remove the specified appointment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        
        // Delete associated payment first
        if ($appointment->payment) {
            $appointment->payment->delete();
        }
        
        // Now delete the appointment
        $appointment->delete();
        
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully');
    }

    /**
     * Show the form for managing available times and unavailable dates.
     *
     * @return \Illuminate\View\View
     */
    public function manageTimes()
    {
        $stats = $this->getAppointmentStats();
        $availableTimes = \App\Models\AvailableTime::orderBy('start_time')->get();
        
        return view('admin.appointments.manage_times', array_merge(
            compact('availableTimes'),
            $stats
        ));
    }

    /**
     * Store newly created available time slots.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAvailableTimes(Request $request)
    {
        $validated = $request->validate([
            'time_slots' => 'required|array',
            'time_slots.*' => 'required|string',
        ]);
        
        foreach ($validated['time_slots'] as $timeSlot) {
            $startTime = $timeSlot;
            $endTime = date('H:i', strtotime($startTime . ' +1 hour'));
            
            \App\Models\AvailableTime::create([
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);
        }
        
        return redirect()->route('admin.appointments.times.manage')
            ->with('success', 'Time slots created successfully');
    }

    /**
     * Remove the specified available time from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAvailableTime($id)
    {
        $availableTime = \App\Models\AvailableTime::findOrFail($id);
        
        // Check if there are any appointments using this time slot
        $hasAppointments = \App\Models\Appointment::where('available_time_id', $id)->exists();
        
        if ($hasAppointments) {
            return redirect()->route('admin.appointments.times.manage')
                ->with('error', 'Cannot delete this time slot because it is being used by one or more appointments.');
        }
        
        // Check if there are any unavailable times using this time slot
        $hasUnavailableTimes = \App\Models\UnavailableTime::where('available_time_id', $id)->exists();
        
        if ($hasUnavailableTimes) {
            // Option 1: Prevent deletion
            // return redirect()->route('admin.appointments.times.manage')
            //     ->with('error', 'Cannot delete this time slot because it has unavailable dates associated with it.');
            
            // Option 2: Delete associated unavailable times first
            \App\Models\UnavailableTime::where('available_time_id', $id)->delete();
        }
        
        // Now safe to delete the available time
        $availableTime->delete();
        
        return redirect()->route('admin.appointments.times.manage')
            ->with('success', 'Time slot deleted successfully');
    }

    /**
     * Store newly created unavailable dates.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUnavailableTimes(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'available_time_ids' => 'required|array',
            'available_time_ids.*' => 'required|exists:available_times,available_time_id',
        ]);
        
        foreach ($validated['available_time_ids'] as $timeId) {
            \App\Models\UnavailableTime::create([
                'available_time_id' => $timeId,
                'date' => $validated['date'],
            ]);
        }
        
        return redirect()->route('admin.appointments.times.manage')
            ->with('success', 'Unavailable times marked successfully');
    }

    /**
     * Remove the specified unavailable time from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUnavailableTime($id)
    {
        $unavailableTime = \App\Models\UnavailableTime::findOrFail($id);
        $unavailableTime->delete();
        
        return redirect()->route('admin.appointments.times.manage')
            ->with('success', 'Unavailable time removed successfully');
    }
    /**
     * Get unavailable times for a specific date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnavailableTimes(Request $request)
    {
        $date = $request->query('date');
        
        if (!$date) {
            return response()->json([]);
        }
        
        $unavailableTimes = \App\Models\UnavailableTime::where('date', $date)->get();
        
        return response()->json($unavailableTimes);
    }
}