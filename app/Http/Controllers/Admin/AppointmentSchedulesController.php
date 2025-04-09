<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment; // Assuming Appointment model exists

class AppointmentSchedulesController extends Controller
{
    /**
     * Display a list of all appointment schedules.
     */
    public function index()
    {
        // Fetch all appointments from the database
        $appointments = Appointment::all();

        // Pass data to the admin view
        return view('admin.appointmentSchedules', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        return view('admin.create_appointment');
    }

    /**
     * Store a newly created appointment in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'status' => 'required|string|in:Pending,Confirmed,Completed,Canceled',
        ]);

        Appointment::create($request->all());

        return redirect()->route('admin.appointment_schedules')
                         ->with('success', 'Appointment created successfully.');
    }

    /**
     * Show the form for editing an existing appointment.
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);

        return view('admin.edit_appointment', compact('appointment'));
    }

    /**
     * Update the specified appointment in the database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'status' => 'required|string|in:Pending,Confirmed,Completed,Canceled',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->all());

        return redirect()->route('admin.appointment_schedules')
                         ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment from the database.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointment_schedules')
                         ->with('success', 'Appointment deleted successfully.');
    }
}
