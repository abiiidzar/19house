<div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_24rem]">
    <section class="min-w-0">
        <header class="mb-7 flex flex-col justify-between gap-4 border-b border-neutral-300 pb-6 sm:flex-row sm:items-end">
            <div><p class="page-kicker">Register / New order</p><h1 class="mt-3">New Sale</h1><p class="mt-2 text-sm text-neutral-500">Select a product, color, and available size.</p></div>
            <span class="text-xs uppercase tracking-[0.12em] text-neutral-500">{{ $products->total() }} products</span>
        </header>
        <div class="border border-neutral-200 bg-white p-4 sm:p-5">
            <label for="pos-search" class="page-kicker mb-2 block">Search catalogue</label>
            <div class="relative"><svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input id="pos-search" type="search" wire:model.live.debounce.300ms="search" autocomplete="off" placeholder="Search product name or SKU" class="w-full border border-neutral-300 py-3 pl-11 pr-4 text-sm focus:border-black focus:outline-none"></div>
        </div>
        <div class="mt-5 grid gap-4 md:grid-cols-2" aria-live="polite">
            @forelse($productCards as ['product' => $product, 'selectedVariantId' => $selectedVariantId, 'variant' => $variant, 'selectedSku' => $selectedSku, 'available' => $available, 'selectedPrice' => $selectedPrice])
                <article wire:key="product-{{ $product->id }}" class="flex min-w-0 flex-col border border-neutral-200 bg-white p-5 transition-colors hover:border-neutral-400">
                    <div class="flex items-start justify-between gap-3"><div class="min-w-0"><h2 class="font-medium">{{ $product->name }}</h2><p class="mt-1 text-xs text-neutral-500">{{ $product->variants->count() }} colors · {{ $available }} units available</p></div><span class="shrink-0 text-sm">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span></div>
                    <div class="mt-5"><p class="mb-2 text-xs font-medium uppercase tracking-wider text-neutral-500">Color</p><div class="flex flex-wrap gap-2">
                        @foreach($product->variants as $color)
                            <button type="button" wire:key="color-{{ $color->id }}" wire:click="selectVariant({{ $product->id }}, {{ $color->id }})" aria-pressed="{{ $selectedVariantId === $color->id ? 'true' : 'false' }}" class="inline-flex items-center gap-2 border px-3 py-2 text-xs {{ $selectedVariantId === $color->id ? 'border-black bg-neutral-100' : 'border-neutral-200 hover:border-neutral-500' }}">
                                @if($color->hex_code)<span class="h-3 w-3 rounded-full border border-neutral-300" style="background-color: {{ $color->hex_code }}"></span>@endif {{ $color->name }}
                            </button>
                        @endforeach
                    </div></div>
                    <div class="mt-4"><p class="mb-2 text-xs font-medium uppercase tracking-wider text-neutral-500">Size · available stock</p><div class="flex flex-wrap gap-2">
                        @foreach($variant?->skus ?? [] as $sku)
                            @php $stock = $sku->stock?->available ?? 0; @endphp
                            <button type="button" wire:key="size-{{ $sku->id }}" wire:click="selectSize({{ $product->id }}, {{ $sku->id }})" @disabled($stock < 1) title="{{ $sku->sku }}" aria-pressed="{{ $selectedSku?->id === $sku->id ? 'true' : 'false' }}" class="border px-3 py-2 text-xs {{ $selectedSku?->id === $sku->id ? 'border-black bg-neutral-100' : 'border-neutral-200 hover:border-neutral-500' }} disabled:cursor-not-allowed disabled:opacity-40">{{ $sku->size->name }} <span class="text-neutral-500">({{ $stock }})</span></button>
                        @endforeach
                    </div></div>
                    <div class="mt-auto pt-5">
                        @if($selectedSku)<p class="mb-2 text-xs text-neutral-500">{{ $selectedSku->sku }} · Rp {{ number_format($selectedPrice, 0, ',', '.') }} · Available: {{ $selectedSku->stock?->available ?? 0 }}</p>@else<p class="mb-2 text-xs text-neutral-500">Select an available size</p>@endif
                        <button type="button" wire:click="addProductToCart({{ $product->id }})" wire:loading.attr="disabled" @disabled(! $selectedSku || ($selectedSku->stock?->available ?? 0) < 1) class="w-full bg-black px-4 py-3 text-xs font-medium uppercase tracking-wider text-white hover:bg-neutral-800 disabled:cursor-not-allowed disabled:opacity-40">Add to cart</button>
                    </div>
                </article>
            @empty
                <p class="py-8 text-center text-sm text-neutral-500 md:col-span-2">{{ trim($search) !== '' ? 'No active products match this search.' : 'No active products are available for POS yet.' }}</p>
            @endforelse
        </div>
        @if($products->hasPages())<div class="mt-6">{{ $products->links() }}</div>@endif
    </section>
    <section class="min-w-0 overflow-hidden border border-neutral-300 bg-white xl:sticky xl:top-6 xl:max-h-[calc(100vh-3rem)] xl:overflow-y-auto">
        <div class="flex items-end justify-between bg-[#111111] p-5 text-white"><div><p class="text-[10px] uppercase tracking-[0.16em] text-neutral-500">Order summary</p><h2 class="mt-2 text-xl font-semibold">Current Sale</h2></div><span class="flex h-8 min-w-8 items-center justify-center rounded-full border border-neutral-700 px-2 text-xs">{{ count($cart) }}</span></div>
        <div class="p-4 sm:p-5">
        @if($errors->any())<div role="alert" class="mb-4 border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
        <div class="max-h-[45vh] space-y-3 overflow-y-auto border-b border-neutral-200 pb-5">
            @forelse($cart as $skuId => $item)
                <div wire:key="cart-{{ $skuId }}" class="border border-neutral-200 bg-neutral-50 p-3">
                    <div class="flex justify-between gap-3"><div><p class="text-sm font-medium">{{ $item['name'] }}</p><p class="text-xs text-neutral-500">{{ $item['variant'] }} / {{ $item['size'] }}</p></div><p class="text-sm">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p></div>
                    <div class="mt-3 flex items-center gap-2"><button type="button" wire:click="updateQty({{ $skuId }}, {{ $item['quantity'] - 1 }})" class="control-button" aria-label="Decrease quantity">−</button><span class="min-w-8 text-center text-sm">{{ $item['quantity'] }}</span><button type="button" wire:click="updateQty({{ $skuId }}, {{ $item['quantity'] + 1 }})" class="control-button" aria-label="Increase quantity">+</button><button type="button" wire:click="removeFromCart({{ $skuId }})" class="ml-auto min-h-11 px-2 text-xs text-red-700 underline">Remove</button></div>
                </div>
            @empty<p class="py-7 text-center text-sm text-neutral-500">Cart is empty.</p>@endforelse
        </div>
        <div class="mt-5 space-y-3 text-sm">
            <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
            @if(auth()->user()->hasPermission('pos.discount.apply'))<div><label for="pos-discount" class="mb-1 block text-xs uppercase tracking-wider">Discount (max 10%, Rp 50.000)</label><input id="pos-discount" type="number" min="0" step="1" wire:model.live.debounce.300ms="discount" class="w-full border border-neutral-300 p-2 text-sm"></div>@endif
            <div class="flex justify-between border-t border-neutral-300 pt-4 text-xl font-semibold"><span>Total</span><span class="tabular-nums">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
            <div><label for="pos-method" class="mb-1 block text-xs uppercase tracking-wider">Payment Method</label><select id="pos-method" wire:model.live="paymentMethod" class="w-full border border-neutral-300 p-2"><option value="CASH">Cash</option><option value="QRIS">QRIS (verified manually)</option><option value="TRANSFER">Transfer (verified manually)</option></select></div>
            @if($paymentMethod === 'CASH')
                <div x-data="{ raw: $wire.entangle('amountReceived').live, formatted: '' }" x-init="formatted = raw === '' ? '' : Number(raw).toLocaleString('id-ID'); $watch('raw', value => { const digits = String(value ?? '').replace(/\D/g, ''); const next = digits === '' ? '' : Number(digits).toLocaleString('id-ID'); if (formatted !== next) formatted = next })">
                    <label for="pos-received" class="mb-1 block text-xs uppercase tracking-wider">Amount Received</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-neutral-500">Rp</span>
                        <input id="pos-received" type="text" inputmode="numeric" autocomplete="off" maxlength="19" x-bind:value="formatted" x-on:input="const digits = $event.target.value.replace(/\D/g, '').replace(/^0+(?=\d)/, '').slice(0, 15); raw = digits; formatted = digits === '' ? '' : Number(digits).toLocaleString('id-ID'); $event.target.value = formatted" placeholder="0" class="w-full border border-neutral-300 py-2 pl-10 pr-3 tabular-nums">
                    </div>
                    <p class="mt-1.5 text-[11px] text-neutral-500">Use numbers only; separators are added automatically.</p>
                </div>
                @if(is_numeric($amountReceived) && (int) $amountReceived >= $total && $total > 0)<p class="text-sm text-green-700">Change: Rp {{ number_format((int) $amountReceived - $total, 0, ',', '.') }}</p>@endif
            @else
                <div><label for="pos-reference" class="mb-1 block text-xs uppercase tracking-wider">Provider / Bank Reference</label><input id="pos-reference" type="text" maxlength="100" wire:model="paymentReference" class="w-full border border-neutral-300 p-2" placeholder="Verified transaction reference"></div>
                <label class="flex items-start gap-2 text-sm"><input type="checkbox" wire:model="nonCashConfirmed" class="mt-1"><span>I have verified the payment on the QRIS/bank provider. This records a manual confirmation; it does not collect funds.</span></label>
            @endif
            <button type="button" wire:click="checkout" wire:loading.attr="disabled" @disabled($cart === []) class="w-full bg-black px-5 py-4 text-xs font-medium uppercase tracking-[0.08em] text-white hover:bg-neutral-800 disabled:cursor-not-allowed disabled:opacity-50">Complete Sale</button>
        </div>
        </div>
    </section>
</div>
