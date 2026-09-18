<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentWebhookService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request, PaymentWebhookService $service): Response
    {
        // Validasi signature gateway di sini (untuk MVP kita lewati)

        $paymentReference = $request->validate([
            'payment_reference' => ['required', 'string', 'max:100'],
        ])['payment_reference'];

        try {
            $service->handlePaidWebhook($paymentReference);
            return response('OK', 200);
        } catch (\Exception $e) {
            return response('Error', 500);
        }
    }
}
