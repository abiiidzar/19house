@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="page-kicker">Catalogue</p>
            <h1 class="mt-2 text-2xl font-semibold">Products</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage product catalogue, pricing, variants, and availability.</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="bg-black px-5 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">ADD PRODUCT</a>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('admin.products.index') }}" class="grid gap-2 border-y border-neutral-200 py-4 sm:grid-cols-2 xl:grid-cols-[minmax(16rem,1fr)_12rem_14rem_auto]">
        <label class="sr-only" for="product-search">Search products</label>
        <input id="product-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search product name" class="w-full">

        <label class="sr-only" for="product-status">Status</label>
        <select id="product-status" name="status">
            <option value="">All statuses</option>
            @foreach(['ACTIVE', 'DRAFT', 'INACTIVE', 'ARCHIVED'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
            @endforeach
        </select>

        <label class="sr-only" for="product-category">Category</label>
        <select id="product-category" name="category">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-black px-5 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">FILTER</button>
    </form>

    {{-- PRODUCT TABLE --}}
    <div class="overflow-x-auto border border-neutral-200 bg-white">
        <table class="w-full min-w-[60rem] text-left text-sm">
            <thead>
                <tr class="border-b border-neutral-200 bg-neutral-50 text-xs uppercase tracking-wider text-neutral-500">
                    <th class="px-5 py-4">Product</th>
                    <th class="px-5 py-4">Category</th>
                    <th class="px-5 py-4 text-right">Price</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Featured</th>
                    <th class="px-5 py-4">Updated</th>
                    <th class="px-5 py-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr class="border-b border-neutral-200 transition last:border-b-0 hover:bg-neutral-50">
                        <td class="px-5 py-4">
                            <div class="font-medium text-neutral-950">{{ $product->name }}</div>
                            <div class="mt-1 text-xs text-neutral-500">{{ $product->slug }}</div>
                        </td>

                        <td class="px-5 py-4">{{ $product->category?->name ?? '-' }}</td>

                        <td class="px-5 py-4 text-right font-medium tabular-nums">Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>

                        <td class="px-5 py-4">
                            <span class="status-badge">{{ $product->status }}</span>
                        </td>

                        <td class="px-5 py-4">
                            @if($product->is_featured)
                                <span class="text-xs font-medium uppercase tracking-wider">Featured</span>
                            @else
                                <span class="text-neutral-400">—</span>
                            @endif
                        </td>

                        <td class="whitespace-nowrap px-5 py-4 text-neutral-500">{{ $product->updated_at->format('d M Y') }}</td>

                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('admin.products.show', $product) }}" class="text-xs font-medium uppercase tracking-wider hover:underline">View</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-medium uppercase tracking-wider hover:underline">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <p class="font-medium">No products found.</p>
                            <p class="mt-1 text-sm text-neutral-500">No products match the current filters.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div>{{ $products->links() }}</div>

</div>
@endsection
