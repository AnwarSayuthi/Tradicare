<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentSchedulesController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['user', 'service'])->get();
        return view('admin.appointmentSchedules', compact('appointments'));
    }

    public function create()
    {
        $services = Service::where('active', true)->get();
        $users = User::where('role', 'customer')->get();
        return view('admin.appointments.create', compact('services', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,service_id',
            'appointment_date' => 'required|date',
            'end_time' => 'required|date',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        Appointment::create($validated);
        return redirect()->route('admin.appointmentSchedules')->with('success', 'Appointment created successfully');
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $services = Service::where('active', true)->get();
        $users = User::where('role', 'customer')->get();
        return view('admin.appointments.edit', compact('appointment', 'services', 'users'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,service_id',
            'appointment_date' => 'required|date',
            'end_time' => 'required|date',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $appointment->update($validated);
        return redirect()->route('admin.appointmentSchedules')->with('success', 'Appointment updated successfully');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return redirect()->route('admin.appointmentSchedules')->with('success', 'Appointment deleted successfully');
    }
}
