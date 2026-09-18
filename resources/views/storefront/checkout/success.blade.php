@extends('layouts.storefront')
@section('title', 'Order Status - 19HOUSE')

@section('content')
<section class="max-w-3xl mx-auto px-6 py-20 text-center">
    @if($order->status === 'PENDING_PAYMENT')
        <h1 class="storefront-section-title text-yellow-600">Awaiting Payment</h1>
        <p class="mt-2 text-sm text-neutral-500">Please complete your payment of <span class="font-normal">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
        <p class="mt-1 text-sm text-neutral-500">Order No: <span class="font-mono">{{ $order->order_number }}</span></p>

        @if(isset($paymentUrl))
        <a href="{{ $paymentUrl }}" class="storefront-button mt-8 inline-block bg-black text-white px-8 py-4 hover:bg-neutral-800">PAY NOW</a>
        @endif
    @else
        <h1 class="storefront-section-title text-green-600">Payment Successful!</h1>
        <p class="mt-2 text-sm text-neutral-500">Your order is now being processed.</p>
        <p class="mt-1 text-sm text-neutral-500">Order No: <span class="font-mono">{{ $order->order_number }}</span></p>

        <a href="{{ route('home') }}" class="storefront-button mt-8 inline-block bg-black text-white px-8 py-4 hover:bg-neutral-800">CONTINUE SHOPPING</a>
    @endif
    <a href="{{ route('customer.orders.show', $order) }}" class="storefront-button mt-6 inline-block underline">View Order & Tracking</a>
</section>
@endsection
