<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        return view('storefront.checkout.index');
    }

    public function success(Order $order): View
    {
        abort_unless(auth()->id() === $order->user_id, 403);

        $paymentUrl = null;
        if ($order->status === Order::STATUS_PENDING_PAYMENT && $order->payment) {
            $paymentUrl = app(\App\Services\Payment\PaymentService::class)->getPaymentUrl($order->payment);
        }

        $order->load(['shipment', 'cancellationRequests' => fn ($query) => $query->latest(), 'histories' => fn ($query) => $query->latest()]);

        return view('storefront.checkout.success', compact('order', 'paymentUrl'));
    }
}
