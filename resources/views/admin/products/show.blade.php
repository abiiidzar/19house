@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="space-y-8">

    {{-- PAGE HEADER --}}
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Products</a>

        <div class="mt-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="page-kicker">Product</p>

                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
                    <span class="status-badge">{{ $product->status }}</span>
                </div>

                <p class="mt-1 text-sm text-neutral-500">{{ $product->slug }}</p>
            </div>

            <a href="{{ route('admin.products.edit', $product) }}" class="border border-black px-5 py-3 text-sm font-medium transition hover:bg-black hover:text-white">EDIT PRODUCT</a>
        </div>
    </div>

    {{-- OVERVIEW --}}
    <section>
        <div class="mb-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Overview</p>
        </div>

        <div class="grid border border-neutral-200 bg-white sm:grid-cols-2 xl:grid-cols-4">
            <div class="border-b border-neutral-200 p-5 sm:border-r xl:border-b-0">
                <p class="text-xs uppercase tracking-wider text-neutral-500">Category</p>
                <p class="mt-2 font-medium">{{ $product->category?->name ?? 'No category' }}</p>
            </div>

            <div class="border-b border-neutral-200 p-5 xl:border-b-0 xl:border-r">
                <p class="text-xs uppercase tracking-wider text-neutral-500">Base Price</p>
                <p class="mt-2 font-semibold tabular-nums">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
            </div>

            <div class="border-b border-neutral-200 p-5 sm:border-b-0 sm:border-r">
                <p class="text-xs uppercase tracking-wider text-neutral-500">Status</p>
                <p class="mt-2 font-medium">{{ $product->status }}</p>
            </div>

            <div class="p-5">
                <p class="text-xs uppercase tracking-wider text-neutral-500">Featured</p>
                <p class="mt-2 font-medium">{{ $product->is_featured ? 'YES' : 'NO' }}</p>
            </div>
        </div>
    </section>

    {{-- COLLECTIONS --}}
    <section class="border-t border-neutral-200 pt-6">
        <div class="grid gap-5 md:grid-cols-[14rem_1fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Collections</p>
                <p class="mt-1 text-sm text-neutral-500">Collections containing this product.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @forelse($product->collections as $collection)
                    <span class="border border-neutral-300 bg-white px-3 py-2 text-sm">{{ $collection->name }}</span>
                @empty
                    <p class="text-sm text-neutral-500">No collections assigned.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- DESCRIPTION --}}
    <section class="border-t border-neutral-200 pt-6">
        <div class="grid gap-5 md:grid-cols-[14rem_1fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Description</p>
                <p class="mt-1 text-sm text-neutral-500">Product information displayed to customers.</p>
            </div>

            <div class="max-w-3xl">
                <p class="whitespace-pre-line leading-7 text-neutral-700">{{ $product->description ?: 'No description.' }}</p>
            </div>
        </div>
    </section>

    {{-- VARIANTS --}}
    <section class="border-t border-neutral-200 pt-6">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Variants & SKUs</p>
            <p class="mt-1 text-sm text-neutral-500">Manage product colors, photography, sizes, SKU codes, and SKU pricing.</p>
        </div>

        @livewire('admin.catalog.product-variant-manager', ['product' => $product])
    </section>

</div>
@endsection
