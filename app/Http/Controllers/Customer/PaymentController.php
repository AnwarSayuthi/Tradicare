<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function processOrderPayment(Request $request, $orderId)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);
        
        if ($order->payment_status == 'paid') {
            return redirect()->route('customer.orders.show', $order->order_id)
                           ->with('error', 'This order has already been paid');
        }
        
        $validated = $request->validate([
            'payment_method' => 'required|string'
        ]);
        
        // Create payment record
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'order_id' => $order->order_id,
            'appointment_id' => null,
            'amount' => $order->total_amount,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'transaction_id' => 'TXN' . uniqid()
        ]);
        
        // Update order payment status
        $order->update(['payment_status' => 'paid']);
        
        return redirect()->route('customer.orders.show', $order->order_id)
                        ->with('success', 'Payment processed successfully');
    }
    
    public function processAppointmentPayment(Request $request, $appointmentId)
    {
        $appointment = Appointment::where('user_id', auth()->id())->findOrFail($appointmentId);
        
        // Check if already paid
        if (Payment::where('appointment_id', $appointment->appointment_id)->exists()) {
            return redirect()->route('customer.appointments.show', $appointment->appointment_id)
                           ->with('error', 'This appointment has already been paid');
        }
        
        $validated = $request->validate([
            'payment_method' => 'required|string'
        ]);
        
        // Get service price
        $amount = $appointment->service->price;
        
        // Create payment record
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'order_id' => null,
            'appointment_id' => $appointment->appointment_id,
            'amount' => $amount,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'transaction_id' => 'TXN' . uniqid()
        ]);
        
        return redirect()->route('customer.appointments.show', $appointment->appointment_id)
                        ->with('success', 'Payment processed successfully');
    }
}