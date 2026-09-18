<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Orders\OrderFulfillmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        abort_if($status !== null && ! in_array($status, [Payment::STATUS_PENDING, Payment::STATUS_PAID, Payment::STATUS_EXPIRED, Payment::STATUS_FAILED, Payment::STATUS_CANCELLED, Payment::STATUS_REFUND_PENDING, Payment::STATUS_REFUNDED], true), 404);
        $payments = Payment::with('order.user')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('payment_reference', 'like', '%'.$search.'%')
                ->orWhere('provider_payment_id', 'like', '%'.$search.'%')
                ->orWhereHas('order', fn ($query) => $query
                    ->where('order_number', 'like', '%'.$search.'%')
                    ->orWhereHas('user', fn ($query) => $query
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')))))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.payments.index', compact('payments', 'status'));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['order.user', 'events']);

        $paidAt = $payment->events->where('event_type', 'PAID')->sortBy('created_at')->first()?->created_at;

        return view('admin.payments.show', compact('payment', 'paidAt'));
    }

    public function confirmRefund(Request $request, Order $order, OrderFulfillmentService $service): RedirectResponse
    {
        $data = $request->validate(['reference' => ['required', 'string', 'max:100']]);
        $service->confirmManualRefund($order, $request->user(), $data['reference']);

        return back()->with('success', 'Manual refund recorded.');
    }
}
