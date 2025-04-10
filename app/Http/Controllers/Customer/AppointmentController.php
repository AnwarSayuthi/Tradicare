<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function create()
    {
        $services = Service::where('active', true)->get();
        return view('customer.appointments', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string'
        ]);

        // Calculate end time based on service duration
        $service = Service::findOrFail($request->service_id);
        $appointmentDate = new \DateTime($request->appointment_date);
        $endTime = clone $appointmentDate;
        $endTime->modify('+' . $service->duration_minutes . ' minutes');

        Appointment::create([
            'user_id' => auth()->id(),
            'service_id' => $request->service_id,
            'appointment_date' => $appointmentDate,
            'end_time' => $endTime,
            'status' => 'scheduled',
            'notes' => $request->notes
        ]);

        return redirect()->route('customer.appointments')->with('success', 'Appointment booked successfully!');
    }

    public function index()
    {
        $appointments = Appointment::where('user_id', auth()->id())
                                  ->with('service')
                                  ->orderBy('appointment_date', 'desc')
                                  ->get();
        return view('customer.appointments.index', compact('appointments'));
    }

    public function show($id)
    {
        $appointment = Appointment::where('user_id', auth()->id())
                                 ->with(['service', 'payment'])
                                 ->findOrFail($id);
        return view('customer.appointments.show', compact('appointment'));
    }

    public function cancel($id)
    {
        $appointment = Appointment::where('user_id', auth()->id())->findOrFail($id);
        $appointment->update(['status' => 'cancelled']);
        return redirect()->route('customer.appointments.index')->with('success', 'Appointment cancelled successfully');
    }
}
