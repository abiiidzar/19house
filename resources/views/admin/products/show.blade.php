@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="space-y-8">

    {{-- HEADER --}}
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Products</a>

        <div class="mt-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="page-kicker">Product</p>

                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>

                    @if($product->status === 'ACTIVE')
                        <span class="text-xs font-medium uppercase tracking-wider text-green-700">Active</span>
                    @elseif($product->status === 'DRAFT')
                        <span class="text-xs font-medium uppercase tracking-wider text-amber-700">Draft</span>
                    @else
                        <span class="text-xs font-medium uppercase tracking-wider text-neutral-500">{{ $product->status }}</span>
                    @endif
                </div>

                <p class="mt-1 text-sm text-neutral-500">{{ $product->slug }}</p>
            </div>

            <a href="{{ route('admin.products.edit', $product) }}" class="bg-black px-5 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">EDIT PRODUCT</a>
        </div>
    </div>

    {{-- OVERVIEW --}}
    <section class="border-t border-neutral-200 pt-6">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4">
            <div class="border border-neutral-200 p-4">
                <p class="text-xs uppercase tracking-wider text-neutral-500">Category</p>
                <p class="mt-2 text-sm font-medium">{{ $product->category?->name ?? 'No category' }}</p>
            </div>

            <div class="border-b border-r border-t border-neutral-200 p-4">
                <p class="text-xs uppercase tracking-wider text-neutral-500">Base Price</p>
                <p class="mt-2 text-sm font-medium">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
            </div>

            <div class="border-b border-r border-t border-neutral-200 p-4">
                <p class="text-xs uppercase tracking-wider text-neutral-500">Variants</p>
                <p class="mt-2 text-sm font-medium">{{ $product->variants->count() }}</p>
            </div>

            <div class="border-b border-r border-t border-neutral-200 p-4">
                <p class="text-xs uppercase tracking-wider text-neutral-500">Featured</p>
                <p class="mt-2 text-sm font-medium">{{ $product->is_featured ? 'YES' : 'NO' }}</p>
            </div>
        </div>
    </section>

    {{-- DESCRIPTION --}}
    <section class="border-t border-neutral-200 pt-6">
        <div class="grid gap-5 md:grid-cols-[12rem_1fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Description</p>
            </div>

            <div class="max-w-3xl">
                <p class="whitespace-pre-line text-sm leading-7 text-neutral-700">{{ $product->description ?: 'No description.' }}</p>
            </div>
        </div>
    </section>

    {{-- COLLECTIONS --}}
    <section class="border-t border-neutral-200 pt-6">
        <div class="grid gap-5 md:grid-cols-[12rem_1fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Collections</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @forelse($product->collections as $collection)
                    <span class="border border-neutral-200 px-3 py-2 text-sm">{{ $collection->name }}</span>
                @empty
                    <p class="text-sm text-neutral-500">No collections assigned.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- VARIANTS --}}
    <section class="border-t border-neutral-200 pt-6">
        <div class="mb-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Variants</p>
            <p class="mt-1 text-sm text-neutral-500">Product colors, images, sizes, SKU codes, pricing, and stock.</p>
        </div>

        <div class="space-y-5">
            @forelse($product->variants as $variant)
                <article class="border border-neutral-200 bg-white">

                    {{-- VARIANT HEADER --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-neutral-200 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 border border-neutral-300" style="background-color: {{ $variant->hex_code ?? '#ffffff' }}"></span>

                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-medium">{{ $variant->name }}</h3>
                                    <span class="text-[10px] font-medium uppercase tracking-wider {{ $variant->is_active ? 'text-green-700' : 'text-neutral-400' }}">{{ $variant->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>

                                <p class="mt-1 text-xs text-neutral-500">{{ $variant->images->count() }} {{ Str::plural('image', $variant->images->count()) }} · {{ $variant->skus->count() }} {{ Str::plural('SKU', $variant->skus->count()) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- IMAGES --}}
                    <div class="border-b border-neutral-200 p-5">
                        <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-neutral-500">Images</p>

                        @if($variant->images->isNotEmpty())
                            <div class="flex flex-wrap gap-3">
                                @foreach($variant->images as $image)
                                    <div class="relative">
                                        <div class="h-32 w-24 overflow-hidden border border-neutral-200 bg-neutral-100">
                                            <img src="{{ Storage::url($image->path) }}" alt="{{ $product->name }} - {{ $variant->name }}" class="h-full w-full object-cover">
                                        </div>

                                        @if($image->is_primary)
                                            <span class="absolute left-1.5 top-1.5 bg-black px-2 py-1 text-[8px] font-medium uppercase tracking-wider text-white">Primary</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-neutral-400">No images uploaded for this variant.</p>
                        @endif
                    </div>

                    {{-- SKUS --}}
                    <div class="p-5">
                        <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-neutral-500">Sizes & SKUs</p>

                        @if($variant->skus->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[34rem] text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-neutral-200 text-[10px] uppercase tracking-wider text-neutral-500">
                                            <th class="py-3 pr-4">Size</th>
                                            <th class="px-4 py-3">SKU</th>
                                            <th class="px-4 py-3 text-right">Price</th>
                                            <th class="py-3 pl-4 text-right">Stock</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($variant->skus as $sku)
                                            <tr class="border-b border-neutral-100 last:border-b-0">
                                                <td class="py-3 pr-4 font-medium">{{ $sku->size?->name ?? '-' }}</td>
                                                <td class="px-4 py-3 font-mono text-xs">{{ $sku->sku }}</td>
                                                <td class="px-4 py-3 text-right tabular-nums">Rp {{ number_format($sku->price ?: $product->base_price, 0, ',', '.') }}</td>
                                                <td class="py-3 pl-4 text-right tabular-nums">{{ $sku->stock?->available ?? 0 }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-neutral-400">No SKUs configured for this variant.</p>
                        @endif
                    </div>

                </article>
            @empty
                <div class="border border-dashed border-neutral-300 px-5 py-10 text-center">
                    <p class="text-sm font-medium">No variants configured.</p>
                    <p class="mt-1 text-xs text-neutral-500">Edit this product to create variants, upload images, and configure SKUs.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection
