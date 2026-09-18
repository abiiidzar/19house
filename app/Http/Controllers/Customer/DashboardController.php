<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()->where('user_id', $request->user()->id);
        $recentOrders = (clone $orders)->latest()->limit(5)->get();
        $pendingPayments = (clone $orders)->where('status', Order::STATUS_PENDING_PAYMENT)->count();
        $processingOrders = (clone $orders)->whereIn('status', [Order::STATUS_PAID, Order::STATUS_PROCESSING, Order::STATUS_READY_TO_SHIP])->count();
        $totalOrders = (clone $orders)->count();
        $unreadNotifications = $request->user()->unreadNotifications()->count();

        return view('customer.dashboard', compact('recentOrders', 'pendingPayments', 'processingOrders', 'totalOrders', 'unreadNotifications'));
    }
}
