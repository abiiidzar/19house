<div class="grid grid-cols-1 gap-10 lg:grid-cols-[minmax(0,1.35fr)_minmax(22rem,.65fr)] lg:gap-16">

    {{-- DYNAMIC GALLERY --}}
    <div class="flex flex-col gap-4">
        @php
            $variant = $product->variants->find($selectedVariantId);
            $images = $variant ? $variant->images : collect();
        @endphp

        @if($images->isEmpty())
            <div class="aspect-[3/4]">
                <x-storefront.image-fallback label="Image for {{ $product->name }} is unavailable" />
            </div>
        @else
            @foreach($images as $img)
                {{-- Tambahkan wire:key untuk mencegah konflik DOM --}}
                <div wire:key="img-{{ $img->id }}" class="aspect-[3/4] bg-neutral-200 overflow-hidden">
                    <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
            @endforeach
        @endif
    </div>

    {{-- PRODUCT INFO --}}
    <div class="self-start lg:sticky lg:top-24 lg:py-8">
        <div class="flex items-center justify-between gap-4">
            <h1 class="product-detail-title">{{ $product->name }}</h1>
            <livewire:storefront.wishlist-button :product="$product" />
        </div>
        <p class="mt-4 text-lg text-neutral-800">Rp {{ number_format($price, 0, ',', '.') }}</p>

        <p class="mt-8 whitespace-pre-line border-t border-neutral-200 pt-6 text-sm leading-7 text-neutral-600">{{ $product->description }}</p>

        <div class="mt-8 space-y-4">
            {{-- VARIANT SELECTOR --}}
            <div>
                <p class="commerce-label mb-3">Color <span class="ml-2 font-normal normal-case tracking-normal text-neutral-500">{{ $variant?->name }}</span></p>
                <div class="flex gap-3">
                    @foreach($product->variants->where('is_active', true)->sortBy('display_order') as $v)
                        <button wire:click="selectVariant({{ $v->id }})"
                                wire:key="var-{{ $v->id }}"
                                type="button"
                                aria-label="Select colour {{ $v->name }}"
                                aria-pressed="{{ $selectedVariantId == $v->id ? 'true' : 'false' }}"
                                class="h-11 w-11 border-2 transition {{ $selectedVariantId == $v->id ? 'border-black ring-1 ring-black ring-offset-2' : 'border-neutral-300' }}"
                                style="background-color: {{ $v->hex_code ?? '#fff' }}"
                                title="{{ $v->name }}">
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- SIZE SELECTOR --}}
            @if($variant)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <p class="commerce-label">Size</p>
                        <details class="relative text-xs text-neutral-500">
                            <summary class="cursor-pointer underline">Size Guide</summary>
                            <div class="absolute right-0 z-10 mt-2 w-56 border border-neutral-200 bg-[#F7F7F5] p-4 text-left shadow-sm">
                                Check the product description for measurements, or contact us if you need help choosing a size.
                            </div>
                        </details>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @foreach($variant->skus->sortBy('size.display_order') as $sku)
                            @php
                                $skuStock = $sku->stock?->available ?? 0;
                                $isDisabled = $skuStock <= 0;
                            @endphp
                            <button wire:click="selectSize({{ $sku->size_id }})"
                                    wire:key="sku-{{ $sku->id }}"
                                    @disabled($isDisabled)
                                    type="button"
                                    aria-pressed="{{ $selectedSizeId == $sku->size_id ? 'true' : 'false' }}"
                                    class="min-h-11 min-w-12 border px-4 py-2 text-sm transition
                                    {{ $selectedSizeId == $sku->size_id ? 'bg-black text-white border-black' : 'border-neutral-300' }}
                                    {{ $isDisabled ? 'opacity-30 cursor-not-allowed line-through' : 'hover:border-black' }}">
                                {{ $sku->size->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- STOCK STATE --}}
            @if($selectedSizeId)
                <p class="text-sm text-neutral-600" aria-live="polite">
                    @if($stockAvailable > 0 && $stockAvailable <= 3) Only {{ $stockAvailable }} left
                    @elseif($stockAvailable > 3) In stock
                    @else <span class="text-red-600">Out of Stock</span> @endif
                </p>
            @endif

            {{-- ADD TO CART PLACEHOLDER --}}
            <button type="button" wire:click="addToCart" wire:loading.attr="disabled" wire:target="addToCart" class="storefront-button mt-6 w-full bg-black py-4 text-white transition hover:bg-neutral-800 disabled:opacity-40" @disabled($stockAvailable <= 0)>
                <span wire:loading.remove wire:target="addToCart">Add to cart</span>
                <span wire:loading wire:target="addToCart">Adding…</span>
            </button>
            <div class="mt-6 grid grid-cols-2 border-y border-neutral-200 text-xs text-neutral-600">
                <a href="{{ route('shipping') }}" class="border-r border-neutral-200 py-4 pr-3 hover:text-black">Shipping information</a>
                <a href="{{ route('returns') }}" class="py-4 pl-3 hover:text-black">Returns & exchanges</a>
            </div>
        </div>
    </div>
</div>
