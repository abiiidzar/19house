@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="space-y-8">

    {{-- HEADER --}}
    <div>
        <a href="{{ route('admin.products.show', $product) }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Product Detail</a>

        <div class="mt-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="page-kicker">Catalogue</p>
                <h1 class="mt-2 text-2xl font-semibold">Edit Product</h1>
                <p class="mt-1 text-sm text-neutral-500">Manage product information, variants, images, sizes, and SKU pricing.</p>
            </div>

            <a href="{{ route('admin.products.show', $product) }}" class="border border-neutral-300 px-5 py-3 text-sm font-medium transition hover:border-black">VIEW PRODUCT</a>
        </div>
    </div>

    {{-- PRODUCT INFORMATION --}}
    <section class="max-w-4xl">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Product Information</p>
            <p class="mt-1 text-sm text-neutral-500">Update the general information and storefront configuration.</p>
        </div>

        <form method="POST" action="{{ route('admin.products.update', $product) }}">
            @method('PUT')
            @include('admin.products._form')
        </form>
    </section>

    {{-- VARIANTS --}}
    <section class="border-t border-neutral-200 pt-8">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Variants, Images & SKUs</p>
            <p class="mt-1 text-sm text-neutral-500">Manage product colors, photography, sizes, SKU codes, and SKU pricing.</p>
        </div>

        @livewire('admin.catalog.product-variant-manager', ['product' => $product])
    </section>

</div>
@endsection
