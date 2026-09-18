@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold">Order Queue</h1>
        <p class="mt-1 text-sm text-neutral-500">Fulfill paid online orders in sequence.</p>
    </div>

    @include('admin._search', ['id' => 'order-search', 'placeholder' => 'Search order number, customer, or email', 'hidden' => ['status' => $status]])

    <nav class="flex flex-wrap gap-2 text-sm" aria-label="Order status filters">
        <a href="{{ route('admin.orders.index', request()->only('search')) }}" class="px-3 py-2 {{ !$status ? 'bg-black text-white' : 'bg-white' }}">All</a>
        @foreach($statuses as $option)
            <a href="{{ route('admin.orders.index', array_filter(['status' => $option, 'search' => request('search')])) }}" class="px-3 py-2 {{ $status === $option ? 'bg-black text-white' : 'bg-white' }}">
                {{ str_replace('_', ' ', $option) }} ({{ $counts[$option] ?? 0 }})
            </a>
        @endforeach
    </nav>

    <div class="overflow-x-auto bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b text-xs uppercase tracking-wider text-neutral-500">
                <tr><th class="px-5 py-4">Order</th><th class="px-5 py-4">Date</th><th class="px-5 py-4">Total</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Action</th></tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b">
                        <td class="px-5 py-4 font-medium">{{ $order->order_number }}</td>
                        <td class="px-5 py-4">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-5 py-4">{{ str_replace('_', ' ', $order->status) }}@if($order->cancellationRequests->contains('status', 'REQUESTED')) <span class="text-amber-700">— cancellation requested</span>@endif</td>
                        <td class="px-5 py-4"><a class="underline" href="{{ route('admin.orders.show', $order) }}">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-neutral-500">No orders in this queue.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</div>
@endsection
