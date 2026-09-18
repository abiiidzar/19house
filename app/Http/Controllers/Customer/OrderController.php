<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\OrderFulfillmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::where('user_id', $request->user()->id)->with('shipment')->latest()->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        Gate::authorize('view', $order);
        $order->load(['items', 'payment', 'shipment', 'voucherUsage.voucher', 'cancellationRequests', 'histories']);

        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order, OrderFulfillmentService $service): RedirectResponse
    {
        Gate::authorize('cancel', $order);
        $data = $request->validate(['reason' => ['required', 'string', 'max:1000']]);
        $service->cancelUnpaid($order, $request->user(), $data['reason']);

        return back()->with('success', 'Unpaid order cancelled.');
    }

    public function requestCancellation(Request $request, Order $order, OrderFulfillmentService $service): RedirectResponse
    {
        Gate::authorize('cancel', $order);
        $data = $request->validate(['reason' => ['required', 'string', 'max:1000']]);
        $service->requestCancellation($order, $request->user(), $data['reason']);

        return back()->with('success', 'Cancellation request submitted for admin review.');
    }
}
