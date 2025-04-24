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
        $query = Appointment::with(['user', 'service']);
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('appointment_date', $request->date);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('appointment_id', 'like', "%{$search}%");
        }
        
        // Get appointments with pagination
        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(10);
        
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
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'duration' => 'required|integer|min:15',
            'notes' => 'nullable|string',
        ]);
        
        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);
        
        // Calculate end time based on duration (in minutes)
        $endTime = (clone $appointmentDateTime)->addMinutes($validated['duration']);
        
        // Create appointment
        Appointment::create([
            'user_id' => $validated['user_id'],
            'service_id' => $validated['service_id'],
            'appointment_date' => $appointmentDateTime,
            'end_time' => $endTime,
            'status' => 'scheduled',
            'notes' => $validated['notes'],
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
        $appointment = Appointment::with(['user', 'service'])->findOrFail($id);
        $stats = $this->getAppointmentStats();
        
        return view('admin.appointments.show', array_merge(
            compact('appointment'),
            $stats
        ));
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
            'appointment_time' => 'required',
            'duration' => 'required|integer|min:15',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);
        
        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);
        
        // Calculate end time based on duration (in minutes)
        $endTime = (clone $appointmentDateTime)->addMinutes($validated['duration']);
        
        $appointment->update([
            'user_id' => $validated['user_id'],
            'service_id' => $validated['service_id'],
            'appointment_date' => $appointmentDateTime,
            'end_time' => $endTime,
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
        $appointment->delete();
        
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully');
    }
}