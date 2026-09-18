<div x-data="{ show: false, message: '' }" x-on:checkout-error.window="message = $event.detail.message; show = true" x-init="setTimeout(() => show = false, 5000)">
    <div x-show="show" x-transition class="mb-6 border border-red-200 bg-red-50 p-4 text-sm text-red-800" style="display:none;">
        <span x-text="message"></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

        {{-- LEFT: Details --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- Contact --}}
            <section>
                <h2 class="text-xs font-medium uppercase tracking-[0.08em] text-neutral-500 mb-4">Contact</h2>
                <div class="border border-neutral-200 p-4 text-sm">
                    <p class="font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-neutral-600">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->phone)
                        <p class="text-neutral-600">{{ auth()->user()->phone }}</p>
                    @endif
                </div>
            </section>

            {{-- Address --}}
            <section>
                <h2 class="text-xs font-medium uppercase tracking-[0.08em] text-neutral-500 mb-4">1. Shipping Address</h2>
                <div class="space-y-3">
                    @forelse($addresses as $addr)
                    <label class="flex items-start gap-3 border p-4 cursor-pointer hover:border-black {{ $selectedAddressId == $addr->id ? 'border-black bg-neutral-50' : 'border-neutral-200' }}">
                        <input type="radio" wire:model="selectedAddressId" value="{{ $addr->id }}" class="mt-1">
                        <div>
                            <p class="font-medium text-sm">{{ $addr->recipient_name }} <span class="text-neutral-400">({{ $addr->phone }})</span></p>
                            <p class="text-sm text-neutral-600">{{ $addr->address_line_1 }}, {{ $addr->city }} {{ $addr->postal_code }}</p>
                        </div>
                    </label>
                    @empty
                    <p class="text-sm text-red-500">No address found. Please add one first.</p>
                    @endforelse
                </div>
                <a href="{{ route('customer.addresses.index') }}" class="mt-3 inline-block text-xs underline">Manage Addresses</a>
            </section>

            {{-- Shipping --}}
            <section>
                <h2 class="text-xs font-medium uppercase tracking-[0.08em] text-neutral-500 mb-4">2. Shipping Method</h2>
                <div class="space-y-3">
                    @foreach($shippingMethods as $key => $method)
                    <label class="flex items-center justify-between border p-4 cursor-pointer hover:border-black {{ $selectedShipping == $key ? 'border-black bg-neutral-50' : 'border-neutral-200' }}">
                        <div class="flex items-center gap-3">
                            <input type="radio" wire:model="selectedShipping" value="{{ $key }}">
                            <span class="text-sm font-medium">{{ $method['name'] }}</span>
                        </div>
                        <span class="text-sm">Rp {{ number_format($method['cost'], 0, ',', '.') }}</span>
                    </label>
                    @endforeach
                </div>
            </section>

            {{-- Payment --}}
            <section>
                <h2 class="text-xs font-medium uppercase tracking-[0.08em] text-neutral-500 mb-4">3. Payment Method</h2>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 border p-4 cursor-pointer hover:border-black">
                        <input type="radio" wire:model="paymentMethod" value="BANK_TRANSFER">
                        <span class="text-sm">Bank transfer</span>
                    </label>
                    <label class="flex items-center gap-3 border p-4 cursor-pointer hover:border-black">
                        <input type="radio" wire:model="paymentMethod" value="COD">
                        <span class="text-sm">Cash on delivery</span>
                    </label>
                </div>
            </section>

            {{-- Voucher --}}
            <section>
                <h2 class="text-xs font-medium uppercase tracking-[0.08em] text-neutral-500 mb-4">Voucher</h2>
                <div class="border border-neutral-200 p-4">
                    <label for="checkout-voucher" class="block text-xs font-medium uppercase tracking-[0.08em] text-neutral-500">Voucher code</label>
                    <div class="mt-2 flex gap-2">
                        <input id="checkout-voucher" type="text" maxlength="50" wire:model.live.debounce.300ms="voucherCode" autocomplete="off" placeholder="e.g. WELCOME10" class="min-w-0 flex-1 border border-neutral-300 px-3 py-2 text-sm uppercase focus:border-black focus:outline-none">
                        <button type="button" wire:click="applyVoucher" wire:loading.attr="disabled" class="bg-neutral-800 px-4 text-xs font-medium uppercase tracking-wider text-white disabled:opacity-50">Apply</button>
                    </div>
                    @error('voucherCode')<p role="alert" class="mt-2 text-xs text-red-700">{{ $message }}</p>@enderror
                    @if($appliedVoucherCode)
                        <div class="mt-3 flex items-center justify-between gap-3 text-xs text-green-700"><span>{{ $appliedVoucherCode }} applied — save Rp {{ number_format($discount, 0, ',', '.') }}</span><button type="button" wire:click="removeVoucher" class="underline">Remove</button></div>
                    @endif
                    <p class="mt-2 text-xs text-neutral-500">Final discount and availability are checked again when you place the order.</p>
                </div>
            </section>
        </div>

        {{-- RIGHT: Summary --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 border border-neutral-300 bg-white p-5 sm:p-6">
                <h2 class="commerce-label mb-5">Order Summary</h2>
                <div class="mb-5 max-h-80 space-y-4 overflow-y-auto pr-1">
                    @foreach($cartItems as $item)
                    <div class="grid grid-cols-[3.5rem_minmax(0,1fr)] gap-3 border-b border-neutral-200 pb-4 text-sm">
                        <div class="aspect-[3/4] overflow-hidden bg-[#EFEFEC]">
                            @if($item->sku->variant->images->first())
                                <img src="{{ Storage::url($item->sku->variant->images->first()->path) }}" alt="{{ $item->sku->product->name }}" class="h-full w-full object-cover" loading="lazy">
                            @else
                                <x-storefront.image-fallback :label="'Image for '.$item->sku->product->name.' is unavailable'" />
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium">{{ $item->sku->product->name }}</p>
                            <p class="mt-1 text-xs text-neutral-500">{{ $item->sku->variant->name }} / {{ $item->sku->size->name }} · Qty {{ $item->quantity }}</p>
                            <p class="mt-2">Rp {{ number_format(($item->sku->price ?? $item->sku->product->base_price) * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-neutral-200 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span></div>
                    @if($discount > 0)<div class="flex justify-between text-green-700"><span>Voucher discount</span><span>− Rp {{ number_format($discount, 0, ',', '.') }}</span></div>@endif
                    <div class="mt-3 flex justify-between border-t border-neutral-300 pt-4 text-lg font-semibold"><span>Total</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                </div>

                <button wire:click="placeOrder" wire:loading.attr="disabled" class="storefront-button mt-6 w-full bg-black text-white py-4 hover:bg-neutral-800 disabled:opacity-50">
                    <span wire:loading.remove>PLACE ORDER</span>
                    <span wire:loading>PROCESSING...</span>
                </button>
            </div>
        </div>
    </div>
</div>
