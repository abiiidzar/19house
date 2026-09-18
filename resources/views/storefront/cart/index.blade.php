@extends('layouts.storefront')
@section('title', 'Cart - 19HOUSE')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 md:py-16">
    <p class="storefront-nav mb-4 text-neutral-500">Bag / Review</p>
    <h1 class="storefront-section-title mb-8 border-b border-neutral-200 pb-6">Your Cart</h1>

    @if($items->isEmpty())
        <div class="border-y border-neutral-200 py-20 text-center">
            <p class="editorial-text text-3xl text-[#111111]">Your bag is waiting.</p>
            <p class="mt-3 text-sm text-neutral-500">Discover everyday pieces from the latest collection.</p>
            <a href="{{ route('shop.index') }}" class="editorial-link mt-7">Continue shopping <span aria-hidden="true">→</span></a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($items as $item)
            <div class="grid grid-cols-[5rem_minmax(0,1fr)] gap-3 border-b border-neutral-200 pb-6 sm:flex sm:gap-6 {{ $item->is_unavailable ? 'opacity-60' : '' }}">
                <div class="h-28 w-20 shrink-0 bg-neutral-200 sm:h-32 sm:w-24">
                    @if($item->sku?->variant?->images?->first())
                    <img src="{{ Storage::url($item->sku->variant->images->first()->path) }}" alt="{{ $item->sku->product->name }}" class="w-full h-full object-cover">
                    @else
                    <x-storefront.image-fallback :label="'Product image unavailable'" />
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-medium">{{ $item->sku?->product?->name ?? 'Unavailable product' }}</h3>
                    <p class="mt-1 text-sm text-neutral-500">{{ $item->sku?->variant?->name }} / {{ $item->sku?->size?->name }}</p>
                    @if($item->is_unavailable)
                        <p class="mt-2 text-xs text-red-600">This item is no longer available.</p>
                    @elseif($item->max_stock !== null)
                        <p class="mt-2 text-xs text-amber-700">Only {{ $item->max_stock }} available.</p>
                    @endif
                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="mt-3 flex items-center gap-3">
                        @csrf
                        @method('PATCH')
                        <label for="quantity-{{ $item->id }}" class="text-sm">Qty</label>
                        <input id="quantity-{{ $item->id }}" name="quantity" type="number" min="0" value="{{ $item->quantity }}" class="h-11 w-20 border border-neutral-300 px-3 text-sm" @disabled($item->is_unavailable)>
                        <button type="submit" class="min-h-11 px-2 text-sm underline underline-offset-4 disabled:no-underline disabled:opacity-40" @disabled($item->is_unavailable)>Update</button>
                    </form>
                </div>
                <div class="col-span-2 flex items-center justify-between text-right sm:block">
                    <p class="text-sm font-normal">{{ $item->is_unavailable ? 'Unavailable' : 'Rp '.number_format(($item->sku->price ?? $item->sku->product->base_price) * $item->quantity, 0, ',', '.') }}</p>
                    <form method="POST" action="{{ route('cart.destroy', $item->id) }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 underline">Remove</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10 ml-auto max-w-md border-t border-neutral-300 pt-6">
            <div class="flex items-end justify-between gap-4">
                <span class="commerce-label">Subtotal</span>
                <span class="text-xl font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <p class="mt-3 text-xs leading-5 text-neutral-500">Shipping and discounts are calculated during checkout.</p>
        <div class="mt-6 flex flex-col items-start gap-3 sm:items-stretch">
            @if($canCheckout)
                @auth
                    @if(auth()->user()->hasRole('customer'))
                        <a href="{{ route('checkout.index') }}" class="storefront-button w-full bg-black px-8 py-4 text-white hover:bg-neutral-800">Checkout</a>
                    @else
                        <p class="text-sm text-neutral-600">Checkout hanya tersedia untuk akun customer.</p>
                    @endif
                @else
                    <button type="button" x-data @click="$dispatch('auth-required', { action: 'checkout' })" class="storefront-button w-full bg-black px-8 py-4 text-white hover:bg-neutral-800">Checkout</button>
                @endauth
            @else
                <p class="text-sm text-red-700">Perbarui jumlah atau hapus produk yang tidak tersedia sebelum checkout.</p>
                <button type="button" disabled class="storefront-button w-full cursor-not-allowed bg-neutral-400 px-8 py-4 text-white">Checkout</button>
            @endif
        </div>
        </div>
    @endif
</section>
@endsection
