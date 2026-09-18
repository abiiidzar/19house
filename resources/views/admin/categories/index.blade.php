@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold">Categories</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage product categories and storefront organization.</p>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="bg-black px-5 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">ADD CATEGORY</a>
    </div>

    @include('admin._search', ['id' => 'category-search', 'placeholder' => 'Search category name or slug'])

    {{-- SUMMARY --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="border bg-white p-5">
            <p class="text-xs uppercase tracking-wider text-neutral-500">Categories</p>
            <p class="mt-3 text-2xl font-semibold">{{ $categories->total() }}</p>
        </div>

        <div class="border bg-white p-5">
            <p class="text-xs uppercase tracking-wider text-neutral-500">Active on This Page</p>
            <p class="mt-3 text-2xl font-semibold">{{ $categories->getCollection()->where('is_active', true)->count() }}</p>
        </div>

        <div class="border bg-white p-5">
            <p class="text-xs uppercase tracking-wider text-neutral-500">Products on This Page</p>
            <p class="mt-3 text-2xl font-semibold">{{ number_format($categories->getCollection()->sum('products_count')) }}</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto border bg-white">
        <table class="w-full min-w-[48rem] text-left text-sm">
            <thead>
                <tr class="border-b text-xs uppercase tracking-wider text-neutral-500">
                    <th class="px-5 py-4">Category</th>
                    <th class="px-5 py-4 text-center">Products</th>
                    <th class="px-5 py-4 text-center">Order</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Updated</th>
                    <th class="px-5 py-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categories as $category)
                    <tr class="border-b transition last:border-b-0 hover:bg-neutral-50">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-12 shrink-0 overflow-hidden bg-neutral-100">
                                    @if($category->image)
                                        <img src="{{ Storage::disk('public')->url($category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-[10px] uppercase tracking-wider text-neutral-400">No Image</div>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <p class="font-medium text-neutral-900">{{ $category->name }}</p>
                                    <p class="mt-1 truncate text-xs text-neutral-500">{{ $category->slug }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center tabular-nums">{{ number_format($category->products_count) }}</td>
                        <td class="px-5 py-4 text-center tabular-nums">{{ $category->display_order }}</td>

                        <td class="px-5 py-4">
                            @if($category->is_active)
                                <span class="text-xs font-medium uppercase tracking-wider text-green-700">Active</span>
                            @else
                                <span class="text-xs font-medium uppercase tracking-wider text-neutral-400">Inactive</span>
                            @endif
                        </td>

                        <td class="whitespace-nowrap px-5 py-4 text-neutral-500">{{ $category->updated_at->format('d M Y') }}</td>

                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs font-medium uppercase tracking-wider underline underline-offset-4">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <p class="font-medium">No categories yet.</p>
                            <p class="mt-1 text-sm text-neutral-500">Create your first category to organize the product catalogue.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div>{{ $categories->links() }}</div>

</div>
@endsection
