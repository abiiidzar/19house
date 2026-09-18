<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Models\Setting;
use Illuminate\Support\Str;

class PaymentService
{
    public function createPaymentAttempt(Order $order): Payment
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => 'MOCK',
            'payment_reference' => '19PAY-'.strtoupper(Str::random(12)),
            'amount' => $order->total,
            'status' => Payment::STATUS_PENDING,
            'expires_at' => now()->addMinutes((int) Setting::valueFor('payment_expiry_minutes', 60)),
        ]);

        PaymentEvent::create([
            'payment_id' => $payment->id,
            'event_type' => 'CREATED',
            'payload' => ['message' => 'Payment attempt created'],
        ]);

        return $payment;
    }

    public function getPaymentUrl(Payment $payment): string
    {
        // Pada implementasi nyata, ini adalah URL redirect dari Gateway (Midtrans/Xendit)
        return route('mock.payment.pay', ['reference' => $payment->payment_reference]);
    }
}
