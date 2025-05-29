<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Cart;
use App\Services\ToyyibPayService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Process payment for orders or appointments
     * 
     * @param Request $request
     * @param string $type (order|appointment)
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request, $type, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:toyyibpay,cash_on_delivery,cash',
        ]);

        // Handle order payment
        if ($type === 'order') {
            return $this->processOrderPayment($request, $id);
        }
        
        // Handle appointment payment
        if ($type === 'appointment') {
            return $this->processAppointmentPayment($request, $id);
        }
        
        return redirect()->route('landing')
            ->with('error', 'Invalid payment type specified.');
    }
    
    /**
     * Process payment for an order
     * 
     * @param Request $request
     * @param int $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    private function processOrderPayment(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Ensure the order belongs to the logged-in user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('customer.orders')
                ->with('error', 'You do not have permission to process this payment.');
        }
        
        // Create or update payment record
        $payment = Payment::updateOrCreate(
            ['order_id' => $order->order_id],
            [
                'user_id' => auth()->id(),
                'amount' => $order->total_amount,
                'payment_date' => now(),
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'transaction_id' => 'TXN' . time() . rand(1000, 9999)
            ]
        );
        
        //Remove this section - we'll only mark the cart as completed after successful payment
        //Mark cart as completed if it exists
        $cart = Cart::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->first();
        if ($cart) {
            $cart->status = 'completed';
            $cart->save();
        }
        
        // Handle payment based on method
        if ($request->payment_method === 'toyyibpay') {
            // Initiate ToyyibPay payment
            $toyyibpay = new ToyyibPayService();
            $billResponse = $toyyibpay->createBill($payment);

            if (isset($billResponse[0]['BillCode'])) {
                // Update payment with transaction ID
                $payment->update(['transaction_id' => $billResponse[0]['BillCode']]);
                
                // Use the ToyyibPayService to get the correct payment URL
                return redirect($toyyibpay->getPaymentUrl($billResponse[0]['BillCode']));
            }

            // If payment failed
            $payment->update(['status' => 'failed']);
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('customer.orders')
                ->with('error', 'Payment initialization failed. Please try again.');
        } else {
            // For cash on delivery
            $payment->update(['status' => 'pending']);
            $order->update(['payment_status' => 'pending', 'status' => 'processing']);
            
            return redirect()->route('customer.orders')
                ->with('success', 'Order placed successfully! You will pay upon delivery.');
        }
    }
    
    /**
     * Process payment for an appointment
     * 
     * @param Request $request
     * @param int $appointmentId
     * @return \Illuminate\Http\RedirectResponse
     */
    private function processAppointmentPayment(Request $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Ensure the appointment belongs to the logged-in user
        if ($appointment->user_id !== auth()->id()) {
            return redirect()->route('customer.appointments.index')
                ->with('error', 'You do not have permission to process this payment.');
        }
        
        // Create or update payment record
        $payment = Payment::updateOrCreate(
            ['appointment_id' => $appointment->appointment_id],
            [
                'user_id' => auth()->id(),
                'amount' => $appointment->service->price,
                'payment_date' => now(),
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'transaction_id' => 'TXN' . time() . rand(1000, 9999)
            ]
        );
        
        // Handle payment based on method
        if ($request->payment_method === 'toyyibpay') {
            // Initiate ToyyibPay payment
            $toyyibpay = new ToyyibPayService();
            $billResponse = $toyyibpay->createBill($payment);

            if (isset($billResponse[0]['BillCode'])) {
                // Update payment with transaction ID
                $payment->update(['transaction_id' => $billResponse[0]['BillCode']]);
                
                return redirect('https://dev.toyyibpay.com/'.$billResponse[0]['BillCode']);
            }

            // If payment failed
            $payment->delete();
            return redirect()->route('customer.appointments.index')
                ->with('error', 'Payment initialization failed. Please try again.');
        } else {
            // For cash payment
            $payment->update(['status' => 'pending']);
            $appointment->update(['status' => 'scheduled']);
            
            return redirect()->route('customer.appointments.index')
                ->with('success', 'Appointment booked successfully! You will pay with cash on arrival.');
        }
    }
    
    /**
     * Handle payment callback from ToyyibPay
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentCallback(Request $request)
    {
        $billCode = $request->billcode;
        $status = $request->status_id;
    
        $payment = Payment::where('transaction_id', $billCode)->firstOrFail();
        
        if ($status == 1) {
            // Payment successful
            $payment->update(['status' => 'completed']);
            
            // Handle order payment
            if ($payment->order_id) {
                $payment->order->update(['payment_status' => 'paid', 'status' => 'processing']);
                
                // Now mark the cart as completed
                $cart = Cart::where('order_id', $payment->order_id)->first();
                if ($cart) {
                    $cart->status = 'completed';
                    $cart->save();
                }
                
                return redirect()->route('customer.orders')
                    ->with('success', 'Payment successful! Your order is being processed.');
            }
            
            // Handle appointment payment
            if ($payment->appointment_id) {
                $payment->appointment->update(['status' => 'scheduled']);
                return redirect()->route('customer.appointments.index')
                    ->with('success', 'Payment successful! Your appointment has been confirmed.');
            }
        } else {
            // Payment failed
            $payment->update(['status' => 'failed']);
            
            // Handle order payment failure
            if ($payment->order_id) {
                $payment->order->update(['payment_status' => 'failed']);
                return redirect()->route('customer.orders')
                    ->with('error', 'Payment failed. Please try again or contact support.');
            }
            
            // Handle appointment payment failure
            if ($payment->appointment_id) {
                $payment->appointment->update(['status' => 'cancelled']);
                return redirect()->route('customer.appointments.index')
                    ->with('error', 'Payment failed. Please try again or contact support.');
            }
        }
        
        return redirect()->route('landing')
            ->with('error', 'Payment failed. Please try again or contact support.');
    }
}