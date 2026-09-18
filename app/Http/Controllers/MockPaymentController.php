<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payment\PaymentWebhookService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MockPaymentController extends Controller
{
    // Halaman simulasi gateway (user diarahkan ke sini)
    public function pay(Request $request, string $reference): View
    {
        $payment = Payment::where('payment_reference', $reference)->firstOrFail();
        return view('storefront.checkout.mock-gateway', compact('payment'));
    }

    // User klik "Saya Sudah Bayar" -> Memicu Webhook
    public function success(Request $request, PaymentWebhookService $service, string $reference)
    {
        $service->handlePaidWebhook($reference);

        return redirect()->route('checkout.success', ['order' => Payment::where('payment_reference', $reference)->first()->order_id]);
    }
}
