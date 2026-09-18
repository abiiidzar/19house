<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = User::query()->whereHas('role', fn ($query) => $query->where('slug', 'customer'))
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($query) => $query->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')))
            ->withCount('orders')->latest()->paginate(20)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer): View
    {
        abort_unless($customer->hasRole('customer'), 404);
        $addresses = CustomerAddress::where('user_id', $customer->id)->latest()->get();
        $orders = Order::where('user_id', $customer->id)->latest()->paginate(15);
        $paidCount = Order::where('user_id', $customer->id)->whereIn('status', [Order::STATUS_PAID, Order::STATUS_PROCESSING, Order::STATUS_READY_TO_SHIP, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED, Order::STATUS_COMPLETED])->whereHas('payment', fn ($query) => $query->where('status', 'PAID'))->count();

        return view('admin.customers.show', compact('customer', 'addresses', 'orders', 'paidCount'));
    }
}
