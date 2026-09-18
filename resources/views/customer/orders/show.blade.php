@extends('layouts.account')
@section('title', $order->order_number.' - 19HOUSE')

@section('account_content')
<section class="space-y-8">
    <a href="{{ route('customer.orders.index') }}" class="text-sm underline">← My Orders</a>
    <div class="border-b border-neutral-200 pb-4"><h1 class="storefront-section-title">Order {{ $order->order_number }}</h1><p class="mt-2 text-sm">Status: <strong>{{ str_replace('_', ' ', $order->status) }}</strong></p></div>
    @if(session('success'))<p class="border border-green-200 bg-green-50 p-4 text-sm">{{ session('success') }}</p>@endif
    @if($errors->any())<p class="border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</p>@endif

    @include('customer.orders._timeline')

    @if($order->shipment)
        <div class="border border-neutral-200 p-5"><h2 class="mb-3 text-sm font-medium uppercase tracking-[0.08em]">Shipment & Tracking</h2>
            <p class="text-sm">{{ $order->shipment->courier }} · {{ $order->shipment->service }}</p>
            <p class="mt-2 text-sm">Tracking number: <strong>{{ $order->shipment->tracking_number }}</strong></p>
            <p class="mt-1 text-sm text-neutral-500">{{ $order->shipment->status }}</p>
        </div>
    @endif

    <div class="border border-neutral-200 p-5"><h2 class="mb-3 text-sm font-medium uppercase tracking-[0.08em]">Items</h2>
        @foreach($order->items as $item)
            <p class="border-b py-3 text-sm">{{ $item->sku_snapshot['product_name'] ?? 'Product' }} · {{ $item->sku_snapshot['variant_name'] ?? '' }} / {{ $item->sku_snapshot['size_name'] ?? '' }} × {{ $item->quantity }} <span class="float-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span></p>
        @endforeach
        <p class="mt-4 text-right text-sm">Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
        <p class="mt-1 text-right text-sm">Shipping: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
        @if($order->discount > 0)<p class="mt-1 text-right text-sm text-green-700">Voucher {{ $order->voucherUsage?->voucher?->code }}: − Rp {{ number_format($order->discount, 0, ',', '.') }}</p>@endif
        <p class="mt-4 text-right text-sm">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
    </div>

    @if($order->status === \App\Models\Order::STATUS_PENDING_PAYMENT || ($order->status === \App\Models\Order::STATUS_PAID && !$order->cancellationRequests->contains('status', 'REQUESTED')))
        <form method="POST" action="{{ $order->status === \App\Models\Order::STATUS_PENDING_PAYMENT ? route('customer.orders.cancel', $order) : route('customer.orders.request-cancellation', $order) }}" class="space-y-3 border border-neutral-200 p-5">@csrf
            <h2 class="text-sm font-medium">{{ $order->status === \App\Models\Order::STATUS_PENDING_PAYMENT ? 'Cancel unpaid order' : 'Request cancellation' }}</h2>
            <label for="reason" class="block text-sm">Reason</label>
            <textarea id="reason" name="reason" required maxlength="1000" class="w-full border border-neutral-300 p-3 text-sm">{{ old('reason') }}</textarea>
            <button class="storefront-button bg-black px-5 py-3 text-white">{{ $order->status === \App\Models\Order::STATUS_PENDING_PAYMENT ? 'Cancel Order' : 'Submit Request' }}</button>
            @if($order->status === \App\Models\Order::STATUS_PAID)<p class="text-sm text-neutral-500">The admin will review your request. A refund, if approved, is processed manually.</p>@endif
        </form>
    @endif

    @if($order->cancellationRequests->isNotEmpty())
        <div><h2 class="mb-3 text-sm font-medium uppercase tracking-[0.08em]">Cancellation Requests</h2>
            @foreach($order->cancellationRequests as $cancellation)
                <p class="border-b py-2 text-sm">{{ $cancellation->status }} — {{ $cancellation->reason }} @if($cancellation->admin_note)<span class="block text-neutral-500">{{ $cancellation->admin_note }}</span>@endif</p>
            @endforeach
        </div>
    @endif

</section>
@endsection
