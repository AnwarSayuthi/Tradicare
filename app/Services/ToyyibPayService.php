<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Appointment;

class ToyyibPayService
{
    // API configuration from .env
    private $apiUrl;
    private $secretKey;
    private $categoryCode;
    private $isSandbox;
    
    /**
     * Constructor to initialize configuration from .env
     */
    public function __construct()
    {
        $this->isSandbox = env('TOYYIBPAY_SANDBOX', true);
        $this->secretKey = env('TOYYIBPAY_SECRET_KEY');
        $this->categoryCode = env('TOYYIBPAY_CATEGORY_CODE');
        
        if (empty($this->secretKey)) {
            throw new \RuntimeException('ToyyibPay secret key is not configured');
        }

        if (empty($this->categoryCode)) {
            throw new \RuntimeException('ToyyibPay category code is not configured');
        }
        
        // Set API URL based on sandbox mode
        $baseUrl = $this->isSandbox ? 'https://dev.toyyibpay.com' : 'https://toyyibpay.com';
        $this->apiUrl = $baseUrl . '/index.php/api/createBill';
    }
    
    /**
     * Create a bill in ToyyibPay for payment processing
     * 
     * @param Payment $payment The payment record to process
     * @return array|null Response from ToyyibPay API or null on error
     */
    public function createBill(Payment $payment): ?array
    {
        try {
            if (empty($this->secretKey) || empty($this->categoryCode)) {
                throw new \RuntimeException('ToyyibPay configuration is incomplete');
            }

            // Determine payment type and get relevant details
            $paymentType = $this->getPaymentType($payment);
            $billDetails = $this->prepareBillDetails($payment, $paymentType);
            
            // Prepare API parameters
            $params = [
                'userSecretKey' => trim($this->secretKey), // Ensure no whitespace
                'categoryCode' => trim($this->categoryCode), // Ensure no whitespace
                'billName' => trim($billDetails['name']),
                'billDescription' => trim($billDetails['description']),
                'billPriceSetting' => 1, // Add this line - 1 for fixed amount bill
                'billPayorInfo' => 1, // Add this line - 1 to collect payer information
                'billAmount' => (int)($payment->amount * 100), // Convert to cents and ensure integer
                'billReturnUrl' => trim(route('customer.payment.callback')),
                'billCallbackUrl' => trim(route('customer.payment.callback')),
                'billExternalReferenceNo' => trim($billDetails['reference']),
                'billTo' => trim($payment->user->name),
                'billEmail' => trim($payment->user->email),
                'billPhone' => trim($payment->user->tel_number ?? ''),
                'billSplitPayment' => 0,
                'billPaymentChannel' => 0,
                'billContentEmail' => 'Thank you for your payment!',
                'billChargeToCustomer' => 1
            ];
            
            // Validate required parameters
            foreach (['userSecretKey', 'categoryCode', 'billAmount', 'billPriceSetting', 'billPayorInfo'] as $requiredParam) {
                if (empty($params[$requiredParam]) && $params[$requiredParam] !== 0) {
                    throw new \RuntimeException("Required parameter {$requiredParam} is empty");
                }
            }

            // Make API request
            $response = Http::asForm()->post($this->apiUrl, $params);
            
            if ($response->successful()) {
                $responseData = $response->json();
                // Remove or comment out this debug statement in production
                // dd($responseData);
                if (isset($responseData[0]['BillCode'])) {
                    $payment->billcode = $responseData[0]['BillCode'];
                    $payment->transaction_id = $responseData[0]['BillCode'];
                    $payment->payment_details = $responseData[0];
                    $payment->save();
                    return $responseData;
                }
                
                Log::error('ToyyibPay API error: Missing BillCode', [
                    'response' => $responseData,
                    'payment_id' => $payment->payment_id
                ]);
            } else {
                Log::error('ToyyibPay API error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'payment_id' => $payment->payment_id
                ]);
            }

            $this->handlePaymentFailure($payment);
            return null;
            
        } catch (\Exception $e) {
            Log::error('ToyyibPay exception', [
                'message' => $e->getMessage(),
                'payment_id' => $payment->payment_id
            ]);
            
            $this->handlePaymentFailure($payment);
            return null;
        }
    }

    /**
     * Handle payment failure by updating payment and order status
     * 
     * @param Payment $payment The payment record
     */
    private function handlePaymentFailure(Payment $payment): void
    {
        $payment->status = Payment::STATUS_FAILED;
        $payment->save();

        if ($payment->order) {
            $payment->order->payment_status = Order::PAYMENT_FAILED;
            $payment->order->status = Order::STATUS_CANCELLED;
            $payment->order->save();
        }
    }
    
    /**
     * Get the payment URL for a bill
     * 
     * @param string $billCode The bill code from ToyyibPay
     * @return string The payment URL
     */
    public function getPaymentUrl(string $billCode): string
    {
        $baseUrl = $this->isSandbox ? 'https://dev.toyyibpay.com/' : 'https://toyyibpay.com/';
        return $baseUrl . $billCode;
    }
    
    /**
     * Verify a payment callback from ToyyibPay
     * 
     * @param array $data The callback data
     * @return bool Whether the callback is valid
     */
    public function verifyCallback(array $data): bool
    {
        // Verify required fields
        if (!isset($data['billcode']) || !isset($data['status_id'])) {
            return false;
        }
        
        // Find the payment by bill code
        $payment = Payment::where('billcode', $data['billcode'])
                     ->orWhere('transaction_id', $data['billcode'])
                     ->first();
        
        if (!$payment) {
            Log::error('ToyyibPay callback: Payment not found', [
                'billcode' => $data['billcode']
            ]);
            return false;
        }
        
        // Update payment status based on callback
        $statusId = (int) $data['status_id'];
        
        // Status 1 = success, 2 = pending, 3 = failed
        if ($statusId === 1) {
            $payment->status = Payment::STATUS_COMPLETED;
        } elseif ($statusId === 2) {
            $payment->status = Payment::STATUS_PENDING;
        } else {
            $payment->status = Payment::STATUS_FAILED;
        }
        
        // Store callback data in payment details
        $payment->payment_details = array_merge($payment->payment_details ?? [], $data);
        $payment->save();
        
        // Update related order or appointment
        $this->updateRelatedRecords($payment);
        
        return true;
    }
    
    /**
     * Update related records (order or appointment) based on payment status
     * 
     * @param Payment $payment The payment record
     */
    private function updateRelatedRecords(Payment $payment): void
    {
        if ($payment->isOrderPayment() && $payment->order) {
            if ($payment->isCompleted()) {
                $payment->order->payment_status = Order::PAYMENT_PAID;
                $payment->order->status = Order::STATUS_PROCESSING;
            } elseif ($payment->isFailed()) {
                $payment->order->payment_status = Order::PAYMENT_FAILED;
            }
            
            $payment->order->save();
        }
        
        if ($payment->isAppointmentPayment() && $payment->appointment) {
            if ($payment->isCompleted()) {
                $payment->appointment->status = 'scheduled';
            } elseif ($payment->isFailed()) {
                $payment->appointment->status = 'cancelled';
            }
            
            $payment->appointment->save();
        }
    }
    
    /**
     * Determine the type of payment (order or appointment)
     * 
     * @param Payment $payment The payment record
     * @return string The payment type ('order' or 'appointment')
     */
    private function getPaymentType(Payment $payment): string
    {
        return $payment->order_id ? 'order' : 'appointment';
    }
    
    /**
     * Prepare bill details based on payment type
     * 
     * @param Payment $payment The payment record
     * @param string $type The payment type ('order' or 'appointment')
     * @return array Bill details including name, description and reference
     */
    private function prepareBillDetails(Payment $payment, string $type): array
    {
        if ($type === 'order') {
            $order = Order::findOrFail($payment->order_id);
            return [
                'name' => 'Order #' . $payment->order_id,
                'description' => 'Payment for product order at Tradicare',
                'reference' => 'ORDER' . $payment->order_id
            ];
        } else {
            $appointment = Appointment::findOrFail($payment->appointment_id);
            $serviceName = $appointment->service->service_name ?? 'Service';
            return [
                'name' => 'Appointment #' . $payment->appointment_id,
                'description' => 'Payment for ' . $serviceName . ' at Tradicare',
                'reference' => 'APPT' . $payment->appointment_id
            ];
        }
    }
}