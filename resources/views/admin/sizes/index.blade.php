@extends('layouts.admin')

@section('title', 'Sizes')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold">Sizes</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage product sizes used across variants and SKUs.</p>
        </div>

        <a href="{{ route('admin.sizes.create') }}" class="bg-black px-5 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">ADD SIZE</a>
    </div>

    @include('admin._search', ['id' => 'size-search', 'placeholder' => 'Search size, product, or SKU'])

    {{-- TABLE --}}
    <div class="overflow-x-auto border bg-white">
        <table class="w-full min-w-[58rem] text-left text-sm">
            <thead>
                <tr class="border-b text-xs uppercase tracking-wider text-neutral-500">
                    <th class="px-5 py-4">Size</th>
                    <th class="px-5 py-4">Used By Products</th>
                    <th class="px-5 py-4 text-center">Order</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sizes as $size)
                    <tr class="border-b align-top transition last:border-b-0 hover:bg-neutral-50">

                        {{-- SIZE --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 min-w-10 items-center justify-center border border-neutral-200 bg-white px-2 text-sm font-semibold">{{ $size->name }}</div>

                                <div>
                                    <p class="font-medium">{{ $size->name }}</p>
                                    <p class="mt-1 text-xs text-neutral-400">{{ $size->skus->count() }} {{ Str::plural('SKU', $size->skus->count()) }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- PRODUCTS --}}
                        <td class="px-5 py-4">
                            @forelse($size->skus->groupBy('product_id') as $productSkus)
                                @php($product = $productSkus->first()->product)

                                <div @class(['mb-4' => ! $loop->last])>
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($product)
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-medium underline underline-offset-4">{{ $product->name }}</a>
                                        @else
                                            <span class="text-sm font-medium text-neutral-400">Deleted product</span>
                                        @endif

                                        <span class="text-xs text-neutral-400">{{ $productSkus->count() }} {{ Str::plural('SKU', $productSkus->count()) }}</span>
                                    </div>

                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($productSkus as $sku)
                                            <div class="border border-neutral-200 bg-white px-3 py-2">
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs">
                                                    <span class="font-medium">{{ $sku->variant?->name ?? 'No variant' }}</span>
                                                    <span class="text-neutral-300">·</span>
                                                    <span class="font-mono text-neutral-600">{{ $sku->sku }}</span>
                                                </div>

                                                <div class="mt-1 flex flex-wrap items-center gap-x-2 text-xs text-neutral-500">
                                                    <span>Rp {{ number_format($sku->price ?? $product?->base_price ?? 0, 0, ',', '.') }}</span>
                                                    <span class="text-neutral-300">·</span>
                                                    <span>Stock {{ $sku->stock?->available ?? 0 }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <span class="text-sm text-neutral-400">Not used by any product</span>
                            @endforelse
                        </td>

                        {{-- ORDER --}}
                        <td class="px-5 py-4 text-center tabular-nums">{{ $size->display_order }}</td>

                        {{-- STATUS --}}
                        <td class="px-5 py-4">
                            @if($size->is_active)
                                <span class="text-xs font-medium uppercase tracking-wider text-green-700">Active</span>
                            @else
                                <span class="text-xs font-medium uppercase tracking-wider text-neutral-400">Inactive</span>
                            @endif
                        </td>

                        {{-- ACTION --}}
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.sizes.edit', $size) }}" class="text-xs font-medium uppercase tracking-wider underline underline-offset-4">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <p class="font-medium">No sizes yet.</p>
                            <p class="mt-1 text-sm text-neutral-500">Create sizes to use when configuring product SKUs.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
