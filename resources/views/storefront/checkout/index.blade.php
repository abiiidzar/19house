@extends('layouts.storefront')
@section('title', 'Checkout - 19HOUSE')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 md:py-16">
    <p class="storefront-nav mb-4 text-neutral-500">Secure checkout / 19HOUSE</p>
    <h1 class="storefront-section-title mb-8 border-b border-neutral-200 pb-6">Checkout</h1>
    @livewire('checkout.checkout-form')
</section>
@endsection
