<a href="{{ route('products.show', $product) }}" class="group block">
    <div class="relative mb-4 aspect-[3/4] overflow-hidden bg-neutral-200">
        @if($firstImage)
            <img src="{{ Storage::url($firstImage->path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-300 ease-out group-hover:scale-[1.02]" loading="lazy">
        @else
            <x-storefront.image-fallback label="Image for {{ $product->name }} is unavailable" />
        @endif
        @if($soldOut)
            <span class="absolute bottom-3 left-3 bg-[#F7F7F5] px-2 py-1 text-[10px] font-medium tracking-[0.08em]">SOLD OUT</span>
        @endif
    </div>
    <div>
        <h3 class="storefront-product-title">{{ $product->name }}</h3>
        <p class="storefront-price mt-1">Rp {{ number_format($price, 0, ',', '.') }}</p>
    </div>
</a>
