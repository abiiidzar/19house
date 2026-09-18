{{-- resources/views/storefront/shop/index.blade.php --}}
@extends('layouts.storefront')
@section('title', 'Shop All - 19HOUSE')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 md:py-16">
    <div class="mb-8 border-b border-neutral-200 pb-8">
        <p class="storefront-nav mb-4 text-neutral-500">19HOUSE / Catalogue</p>
        <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div><h1 class="storefront-section-title">{{ $heading }}</h1><p class="mt-3 text-sm text-neutral-500">{{ $products->total() }} {{ Str::plural('piece', $products->total()) }}</p></div>

        <form action="{{ route('shop.index') }}" method="GET" class="grid grid-cols-2 gap-2 lg:flex lg:flex-wrap lg:items-center">
            <label class="sr-only" for="search">Search products</label>
            <input id="search" name="search" value="{{ request('search') }}" placeholder="Search products" class="min-w-0 border border-neutral-300 bg-transparent px-4 py-2 text-sm focus:border-black focus:outline-none">
            <select name="category" onchange="this.form.submit()" class="border border-neutral-300 bg-transparent px-4 py-2 text-sm focus:border-black focus:outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="collection" onchange="this.form.submit()" class="border border-neutral-300 bg-transparent px-4 py-2 text-sm focus:border-black focus:outline-none">
                <option value="">All Collections</option>
                @foreach($collections as $collection)
                    <option value="{{ $collection->slug }}" @selected(request('collection') == $collection->slug)>{{ $collection->name }}</option>
                @endforeach
            </select>

            <select name="sort" onchange="this.form.submit()" class="border border-neutral-300 bg-transparent px-4 py-2 text-sm focus:border-black focus:outline-none">
                <option value="latest">Latest</option>
                <option value="price_asc" @selected(request('sort') == 'price_asc')>Price: Low to High</option>
                <option value="price_desc" @selected(request('sort') == 'price_desc')>Price: High to Low</option>
            </select>
            <button class="storefront-button col-span-2 bg-black px-4 py-2 text-white sm:col-span-1">Search</button>
        </form>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-x-3 gap-y-10 sm:gap-x-6 md:grid-cols-3 lg:grid-cols-4">
        @forelse($products as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div class="col-span-full border-y border-neutral-200 py-20 text-center"><p class="editorial-text text-3xl">Nothing matched your selection.</p><p class="mt-3 text-sm text-neutral-500">Try removing a filter or browse the full catalogue.</p><a class="editorial-link mt-7" href="{{ route('shop.index') }}">View all pieces <span aria-hidden="true">→</span></a></div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $products->links() }}
    </div>
</section>
@endsection
