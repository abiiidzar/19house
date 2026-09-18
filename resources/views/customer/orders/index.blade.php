@extends('layouts.account')
@section('title', 'My Orders - 19HOUSE')

@section('account_content')
<section>
    <h1 class="storefront-section-title mb-8 border-b border-neutral-200 pb-4">My Orders</h1>
    <div class="space-y-4">
        @forelse($orders as $order)
            <a href="{{ route('customer.orders.show', $order) }}" class="flex flex-wrap items-center justify-between gap-4 border border-neutral-200 p-5 hover:border-black">
                <span><strong class="text-sm font-medium">{{ $order->order_number }}</strong><span class="mt-1 block text-sm text-neutral-500">{{ $order->created_at->format('d M Y') }}</span></span>
                <span class="status-badge">{{ str_replace('_', ' ', $order->status) }}</span>
                <span class="text-sm font-medium tabular-nums">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </a>
        @empty
            <div class="border-y border-neutral-200 py-16 text-center"><p class="text-sm text-neutral-500">You have no orders yet.</p><a href="{{ route('shop.index') }}" class="editorial-link mt-5">Start shopping <span aria-hidden="true">→</span></a></div>
        @endforelse
    </div>
    <div class="mt-8">{{ $orders->links() }}</div>
</section>
@endsection
