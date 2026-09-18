@extends('layouts.admin')
@section('title', 'Order '.$order->order_number)

@section('content')
<div class="max-w-5xl space-y-8">
    <a href="{{ route('admin.orders.index') }}" class="text-sm underline">← Back to orders</a>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div><h1 class="text-2xl font-semibold">{{ $order->order_number }}</h1><p class="mt-1 text-sm text-neutral-500">{{ $order->created_at->format('d M Y H:i') }}</p></div>
        <span class="bg-white px-4 py-2 text-sm font-medium">{{ str_replace('_', ' ', $order->status) }}</span>
    </div>
    @if($errors->any())<div class="border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>@endif

    <div class="grid gap-6 md:grid-cols-2">
        <section class="bg-white p-6"><h2 class="mb-4 font-semibold">Items</h2>
            @foreach($order->items as $item)
                <p class="border-b py-3 text-sm">{{ $item->sku_snapshot['product_name'] ?? 'Product' }} — {{ $item->sku_snapshot['variant_name'] ?? '' }} / {{ $item->sku_snapshot['size_name'] ?? '' }} × {{ $item->quantity }} <span class="float-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span></p>
            @endforeach
            <p class="mt-4 text-right text-sm">Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
            <p class="mt-1 text-right text-sm">Shipping: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
            @if($order->discount > 0)<p class="mt-1 text-right text-sm text-green-700">Voucher {{ $order->voucherUsage?->voucher?->code }}: − Rp {{ number_format($order->discount, 0, ',', '.') }} ({{ $order->voucherUsage?->status }})</p>@endif
            <p class="mt-4 text-right font-semibold">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </section>
        <section class="bg-white p-6"><h2 class="mb-4 font-semibold">Delivery & Payment</h2>
            <p class="text-sm">{{ $order->address_snapshot['recipient_name'] ?? '' }}<br>{{ $order->address_snapshot['phone'] ?? '' }}<br>{{ $order->address_snapshot['address_line_1'] ?? '' }}<br>{{ $order->address_snapshot['city'] ?? '' }} {{ $order->address_snapshot['postal_code'] ?? '' }}</p>
            <p class="mt-4 text-sm">Shipping: {{ $order->shipping_method }} · Payment: {{ $order->payment?->status ?? '—' }}</p>
            @if($order->shipment)<p class="mt-4 text-sm">{{ $order->shipment->courier }} {{ $order->shipment->service }}<br>Tracking: <strong>{{ $order->shipment->tracking_number }}</strong></p>@endif
        </section>
    </div>

    <section class="bg-white p-6"><h2 class="mb-4 font-semibold">Fulfillment</h2>
        @php
            $next = match($order->status) {
                \App\Models\Order::STATUS_PAID => \App\Models\Order::STATUS_PROCESSING,
                \App\Models\Order::STATUS_PROCESSING => \App\Models\Order::STATUS_READY_TO_SHIP,
                \App\Models\Order::STATUS_SHIPPED => \App\Models\Order::STATUS_DELIVERED,
                \App\Models\Order::STATUS_DELIVERED => \App\Models\Order::STATUS_COMPLETED,
                default => null,
            };
        @endphp
        @if($next)
            <form method="POST" action="{{ route('admin.orders.advance', $order) }}">@csrf
                <input type="hidden" name="status" value="{{ $next }}">
                <button class="bg-black px-5 py-3 text-sm text-white">Move to {{ str_replace('_', ' ', $next) }}</button>
            </form>
        @elseif($order->status === \App\Models\Order::STATUS_READY_TO_SHIP)
            <form method="POST" action="{{ route('admin.orders.ship', $order) }}" class="grid gap-3 md:grid-cols-3">@csrf
                <input name="courier" value="{{ old('courier') }}" placeholder="Courier" required maxlength="100" class="border px-3 py-2 text-sm">
                <input name="service" value="{{ old('service') }}" placeholder="Service" required maxlength="100" class="border px-3 py-2 text-sm">
                <input name="tracking_number" value="{{ old('tracking_number') }}" placeholder="Tracking number" required maxlength="100" class="border px-3 py-2 text-sm">
                <button class="bg-black px-5 py-3 text-sm text-white md:col-span-3">Create shipment and mark shipped</button>
            </form>
        @else<p class="text-sm text-neutral-500">No fulfillment action available.</p>@endif
    </section>

    @if($order->status === \App\Models\Order::STATUS_CANCELLED && $order->payment?->status === \App\Models\Payment::STATUS_REFUND_PENDING)
        <section class="bg-white p-6"><h2 class="mb-2 font-semibold">Manual Refund Pending</h2>
            <p class="mb-4 text-sm text-neutral-500">Complete the refund outside this application first, then record its reference here. This action does not transfer funds.</p>
            <form method="POST" action="{{ route('admin.orders.confirm-refund', $order) }}" class="flex flex-wrap gap-3">@csrf
                <input name="reference" required maxlength="100" placeholder="External refund reference" class="min-w-56 flex-1 border px-3 py-2 text-sm">
                <button class="bg-black px-5 py-3 text-sm text-white">Confirm Refund</button>
            </form>
        </section>
    @endif

    <section class="bg-white p-6"><h2 class="mb-4 font-semibold">Cancellation Requests</h2>
        @forelse($order->cancellationRequests as $cancellation)
            <div class="border-t py-4 text-sm"><p><strong>{{ $cancellation->status }}</strong> · {{ $cancellation->requester?->name }} · {{ $cancellation->requested_at?->format('d M Y H:i') }}</p>
                <p class="mt-2">{{ $cancellation->reason }}</p>
                @if($cancellation->admin_note)<p class="mt-2 text-neutral-500">Admin: {{ $cancellation->admin_note }}</p>@endif
                @if($cancellation->status === 'REQUESTED')
                    <form method="POST" action="{{ route('admin.cancellation-requests.review', $cancellation) }}" class="mt-3 flex flex-wrap gap-2">@csrf
                        <input name="admin_note" placeholder="Admin note (optional)" maxlength="1000" class="min-w-56 flex-1 border px-3 py-2">
                        <button name="decision" value="approve" class="bg-black px-4 py-2 text-white">Approve</button>
                        <button name="decision" value="reject" class="border px-4 py-2">Reject</button>
                    </form>
                @endif
            </div>
        @empty<p class="text-sm text-neutral-500">No requests.</p>@endforelse
    </section>

    <section class="bg-white p-6"><h2 class="mb-4 font-semibold">Status History</h2>
        <ol class="space-y-3 text-sm">@foreach($order->histories->sortByDesc('id') as $history)
            <li class="border-l-2 border-neutral-300 pl-4"><strong>{{ $history->status }}</strong> · {{ $history->created_at->format('d M Y H:i') }} · {{ $history->actor?->name ?? 'System' }}<br>{{ $history->description }}</li>
        @endforeach</ol>
    </section>
</div>
@endsection
