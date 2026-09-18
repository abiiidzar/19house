@extends('layouts.storefront')
@section('title', $product->name . ' - 19HOUSE')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12">

    @livewire('storefront.product-detail', ['product' => $product])

    {{-- Related Products --}}
    <div class="mt-24">
        <h2 class="storefront-section-title mb-8 border-b border-neutral-200 pb-4">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $rel)
                <x-storefront.product-card :product="$rel" />
            @endforeach
        </div>
    </div>
</section>
@endsection
