@extends('layouts.admin')

@section('title', $collection->name)

@section('content')
<div class="space-y-7">

    {{-- HEADER --}}
    <div>
        <a href="{{ route('admin.collections.index') }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Collections</a>

        <div class="mt-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-semibold">{{ $collection->name }}</h1>

                    @if($collection->is_active)
                        <span class="text-xs font-medium uppercase tracking-wider text-green-700">Active</span>
                    @else
                        <span class="text-xs font-medium uppercase tracking-wider text-neutral-400">Inactive</span>
                    @endif
                </div>

                <p class="mt-1 text-sm text-neutral-500">{{ $collection->slug }}</p>
            </div>

            <a href="{{ route('admin.collections.edit', $collection) }}" class="border border-black px-5 py-3 text-sm font-medium transition hover:bg-black hover:text-white">EDIT COLLECTION</a>
        </div>
    </div>

    {{-- COLLECTION OVERVIEW --}}
    <section class="border-t border-neutral-200 pt-6">
        <div class="border bg-white">

            {{-- COLLECTION INFO --}}
            <div class="flex flex-wrap items-center gap-4 border-b p-5">
                @if($collection->image)
                    <div class="h-16 w-14 shrink-0 overflow-hidden bg-neutral-100">
                        <img src="{{ Storage::disk('public')->url($collection->image) }}" alt="{{ $collection->name }}" class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="flex h-16 w-14 shrink-0 items-center justify-center bg-neutral-100">
                        <span class="px-1 text-center text-[8px] uppercase tracking-wider text-neutral-400">No Image</span>
                    </div>
                @endif

                <div class="min-w-0">
                    <p class="font-medium">{{ $collection->name }}</p>
                    <p class="mt-1 text-xs text-neutral-500">{{ $collection->slug }}</p>
                </div>
            </div>

            {{-- STATS --}}
            <div class="grid sm:grid-cols-3">
                <div class="border-b p-4 sm:border-b-0 sm:border-r">
                    <p class="text-xs uppercase tracking-wider text-neutral-500">Products</p>
                    <p class="mt-2 text-xl font-semibold">{{ $collection->products->count() }}</p>
                </div>

                <div class="border-b p-4 sm:border-b-0 sm:border-r">
                    <p class="text-xs uppercase tracking-wider text-neutral-500">Display Order</p>
                    <p class="mt-2 text-xl font-semibold">{{ $collection->display_order }}</p>
                </div>

                <div class="p-4">
                    <p class="text-xs uppercase tracking-wider text-neutral-500">Status</p>
                    <p class="mt-2 text-sm font-medium {{ $collection->is_active ? 'text-green-700' : 'text-neutral-500' }}">{{ $collection->is_active ? 'ACTIVE' : 'INACTIVE' }}</p>
                </div>
            </div>

        </div>

        {{-- DESCRIPTION --}}
        <div class="mt-6">
            <h2 class="text-sm font-semibold">Description</h2>
            <p class="mt-2 max-w-3xl whitespace-pre-line text-sm leading-6 text-neutral-600">{{ $collection->description ?: 'No description.' }}</p>
        </div>
    </section>

    {{-- PRODUCTS --}}
    <section class="border-t border-neutral-200 pt-6">
        <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="font-semibold">Products</h2>
                <p class="mt-1 text-sm text-neutral-500">Products currently assigned to this collection.</p>
            </div>

            <p class="text-xs text-neutral-500">{{ $collection->products->count() }} {{ Str::plural('product', $collection->products->count()) }}</p>
        </div>

        @if($collection->products->isNotEmpty())
            <div class="overflow-x-auto border bg-white">
                <table class="w-full min-w-[42rem] text-left text-sm">
                    <thead>
                        <tr class="border-b text-xs uppercase tracking-wider text-neutral-500">
                            <th class="px-5 py-4">Product</th>
                            <th class="px-5 py-4">Price</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($collection->products as $product)
                            <tr class="border-b transition last:border-b-0 hover:bg-neutral-50">
                                <td class="px-5 py-4">
                                    <p class="font-medium">{{ $product->name }}</p>
                                    <p class="mt-1 text-xs text-neutral-500">{{ $product->slug }}</p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-medium tabular-nums">Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>

                                <td class="px-5 py-4">
                                    @if($product->status === 'ACTIVE')
                                        <span class="text-xs font-medium uppercase tracking-wider text-green-700">Active</span>
                                    @elseif($product->status === 'DRAFT')
                                        <span class="text-xs font-medium uppercase tracking-wider text-amber-700">Draft</span>
                                    @elseif($product->status === 'ARCHIVED')
                                        <span class="text-xs font-medium uppercase tracking-wider text-neutral-400">Archived</span>
                                    @else
                                        <span class="text-xs font-medium uppercase tracking-wider text-neutral-500">{{ $product->status }}</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.products.show', $product) }}" class="text-xs font-medium uppercase tracking-wider underline underline-offset-4">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="border border-dashed border-neutral-300 px-5 py-10 text-center">
                <p class="text-sm font-medium">No products assigned.</p>
                <p class="mt-1 text-xs text-neutral-500">Add products to this collection from the edit page.</p>
                <a href="{{ route('admin.collections.edit', $collection) }}" class="mt-4 inline-block text-xs font-medium uppercase tracking-wider underline underline-offset-4">Edit Collection</a>
            </div>
        @endif
    </section>

</div>
@endsection
