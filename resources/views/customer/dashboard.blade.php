@extends('layouts.account')
@section('title', 'My Account - 19HOUSE')

@section('account_content')
<div class="space-y-8">
    <div><h1 class="storefront-section-title">Dashboard</h1><p class="mt-2 text-sm text-neutral-500">Welcome back, {{ auth()->user()->name }}.</p></div>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="border border-neutral-200 p-5"><p class="storefront-nav text-neutral-500">Pending Payment</p><p class="mt-3 text-3xl">{{ $pendingPayments }}</p></div>
        <div class="border border-neutral-200 p-5"><p class="storefront-nav text-neutral-500">In Progress</p><p class="mt-3 text-3xl">{{ $processingOrders }}</p></div>
        <div class="border border-neutral-200 p-5"><p class="storefront-nav text-neutral-500">All Orders</p><p class="mt-3 text-3xl">{{ $totalOrders }}</p></div>
        <div class="border border-neutral-200 p-5"><p class="storefront-nav text-neutral-500">Unread Updates</p><p class="mt-3 text-3xl">{{ $unreadNotifications }}</p></div>
    </div>
    <section>
        <div class="mb-4 flex items-end justify-between border-b border-neutral-200 pb-3"><h2 class="storefront-section-title">Recent Orders</h2><a href="{{ route('customer.orders.index') }}" class="storefront-button underline">View All</a></div>
        @forelse($recentOrders as $order)
            <a href="{{ route('customer.orders.show', $order) }}" class="flex items-center justify-between gap-4 border-b border-neutral-200 py-4 hover:text-neutral-500">
                <span><strong class="block text-sm font-medium">{{ $order->order_number }}</strong><span class="text-xs text-neutral-500">{{ $order->created_at->format('d M Y') }}</span></span>
                <span class="status-badge">{{ str_replace('_', ' ', $order->status) }}</span>
            </a>
        @empty<div class="border-y border-neutral-200 py-10 text-center"><p class="text-sm text-neutral-500">No orders yet.</p><a href="{{ route('shop.index') }}" class="editorial-link mt-5">Explore the catalogue <span aria-hidden="true">→</span></a></div>@endforelse
    </section>
</div>
@endsection
