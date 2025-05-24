<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ToyyibPayService
{
    public function createBill($payment)
    {
        $params = [
            'userSecretKey' => config('services.toyyibpay.secret_key'),
            'categoryCode' => config('services.toyyibpay.category_code'),
            'billName' => 'Order #'.$payment->order->order_id,
            'billDescription' => 'Payment for order',
            'billAmount' => $payment->amount * 100,
            'billReturnUrl' => route('payment.callback'),
            'billCallbackUrl' => route('payment.callback'),
            'billExternalReferenceNo' => 'ORDER'.$payment->order_id,
            'billTo' => $payment->order->user->name,
            'billEmail' => $payment->order->user->email
        ];

        $response = Http::post('https://dev.toyyibpay.com/index.php/api/createBill', $params);
        return $response->json();
    }
}