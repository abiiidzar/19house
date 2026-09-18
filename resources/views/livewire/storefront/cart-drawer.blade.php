<div x-data="{ open: false, previousFocus: null, openDrawer() { this.previousFocus = document.activeElement; this.open = true; this.$nextTick(() => this.$refs.drawerClose.focus()) }, closeDrawer() { this.open = false; this.$nextTick(() => this.previousFocus?.focus()) } }" x-on:cart-updated.window="openDrawer()" x-on:cart-error.window="$dispatch('storefront-notice', { message: $event.detail.message })" x-on:keydown.escape.window="if (open) closeDrawer()">

    {{-- Tombol Trigger --}}
    <button type="button" @click="openDrawer()" aria-label="Open cart" aria-controls="storefront-cart-drawer" :aria-expanded="open.toString()" class="relative cursor-pointer hover:text-neutral-500">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
        @if($cartCount > 0)
        <span class="absolute -top-1 -right-1 bg-black text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full">{{ $cartCount }}</span>
        @endif
    </button>

    {{-- Drawer Panel --}}
    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-50 bg-black/50" @click.self="closeDrawer()">
        <div id="storefront-cart-drawer" role="dialog" aria-modal="true" aria-label="Your cart" x-on:keydown.tab="window.trapDialogFocus($event, $el)" class="absolute right-0 top-0 flex h-dvh w-full max-w-md flex-col overflow-hidden bg-[#F7F7F5]" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0">

            <div class="flex shrink-0 items-center justify-between border-b border-neutral-200 p-5 sm:p-6">
                <div><p class="page-kicker">19HOUSE / Bag</p><h3 class="mt-2 text-xl font-medium">Your Cart <span class="text-neutral-400">({{ $cartCount }})</span></h3></div>
                <button type="button" x-ref="drawerClose" @click="closeDrawer()" aria-label="Close cart" class="control-button border-0 text-2xl text-neutral-500">&times;</button>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain">
                <div class="flex min-h-full flex-col">
                    <div class="space-y-5 p-5 sm:p-6">
                        @forelse($cartItems as $item)
                        <div class="flex min-h-24 gap-4 border-b border-neutral-200 pb-5">
                            <div class="h-24 w-20 flex-shrink-0 bg-neutral-200">
                        @if($item->sku->variant->images->first())
                        <img src="{{ Storage::url($item->sku->variant->images->first()->path) }}" alt="{{ $item->sku->product->name }}" class="w-full h-full object-cover">
                        @else
                        <x-storefront.image-fallback :label="'Image for '.$item->sku->product->name.' is unavailable'" />
                        @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="truncate text-sm font-medium">{{ $item->sku->product->name }}</h4>
                                <p class="mt-1 text-sm text-neutral-500">{{ $item->sku->variant->name }} / {{ $item->sku->size->name }}</p>
                                <p class="mt-2 text-sm">Rp {{ number_format(($item->sku->price ?? $item->sku->product->base_price) * $item->quantity, 0, ',', '.') }}</p>
                                <div class="mt-3 flex items-center justify-between gap-3">
                                    <div class="flex items-center border border-neutral-300">
                                        <button type="button" wire:click="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})" class="control-button border-0 border-r border-neutral-300" aria-label="Decrease {{ $item->sku->product->name }} quantity">−</button>
                                        <span class="min-w-11 px-2 text-center text-sm" aria-label="Quantity {{ $item->quantity }}">{{ $item->quantity }}</span>
                                        <button type="button" wire:click="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})" class="control-button border-0 border-l border-neutral-300" aria-label="Increase {{ $item->sku->product->name }} quantity">+</button>
                                    </div>
                                    <button wire:click="removeItem({{ $item->id }})" class="text-xs text-red-500 underline">Remove</button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-20 text-center">
                            <p class="editorial-text text-3xl">Your bag is waiting.</p>
                            <p class="mt-3 text-sm text-neutral-500">Pieces you add will appear here.</p>
                        </div>
                        @endforelse
                    </div>

                    @if($cartCount > 0)
                    <div class="mt-auto shrink-0 border-t border-neutral-200 p-5 sm:p-6">
                        <a href="{{ route('cart.index') }}" class="storefront-button block bg-black py-4 text-center text-white hover:bg-neutral-800">VIEW CART</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
