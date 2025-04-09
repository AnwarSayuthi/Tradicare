<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AppointmentController extends Controller
{
    public function create()
    {
        return view('customer.appointments');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'guests' => 'required|integer|min:1',
            'treatment_type' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
        ]);

        // Store or process the booking data here.
        // For simplicity, just returning a success message.
        return back()->with('success', 'Appointment booked successfully!');
    }
}
