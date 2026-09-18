<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\OrderFulfillmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = [Order::STATUS_PAID, Order::STATUS_PROCESSING, Order::STATUS_READY_TO_SHIP, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED, Order::STATUS_COMPLETED, Order::STATUS_CANCELLED];
        $status = $request->query('status');
        abort_if($status !== null && ! in_array($status, $statuses, true), 404);

        $orders = Order::with(['user', 'payment', 'shipment', 'cancellationRequests'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('order_number', 'like', '%'.$search.'%')
                ->orWhereHas('user', fn ($query) => $query
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%'))))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.orders.index', compact('orders', 'statuses', 'status', 'counts'));
    }

    public function show(Order $order): View
    {
        $order->load(['items', 'payment', 'shipment', 'voucherUsage.voucher', 'histories.actor', 'cancellationRequests.requester', 'cancellationRequests.reviewer']);

        return view('admin.orders.show', compact('order'));
    }

    public function advance(Request $request, Order $order, OrderFulfillmentService $service): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:PROCESSING,READY_TO_SHIP,DELIVERED,COMPLETED']]);
        $service->advance($order, $data['status'], $request->user());

        return back()->with('success', 'Order status updated.');
    }
}
