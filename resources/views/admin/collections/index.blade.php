@extends('layouts.admin')

@section('title', 'Collections')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold">Collections</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage product collections and storefront presentation.</p>
        </div>

        <a href="{{ route('admin.collections.create') }}" class="bg-black px-5 py-3 text-sm font-medium text-white transition hover:bg-neutral-800">ADD COLLECTION</a>
    </div>

    @include('admin._search', ['id' => 'collection-search', 'placeholder' => 'Search collection name, slug, or description'])

    {{-- TABLE --}}
    <div class="overflow-x-auto border bg-white">
        <table class="w-full min-w-[52rem] text-left text-sm">
            <thead>
                <tr class="border-b text-xs uppercase tracking-wider text-neutral-500">
                    <th class="px-5 py-4">Collection</th>
                    <th class="px-5 py-4 text-center">Products</th>
                    <th class="px-5 py-4 text-center">Order</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Updated</th>
                    <th class="px-5 py-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($collections as $collection)
                    <tr class="border-b transition last:border-b-0 hover:bg-neutral-50">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-14 shrink-0 overflow-hidden bg-neutral-100">
                                    @if($collection->image)
                                        <img src="{{ Storage::disk('public')->url($collection->image) }}" alt="{{ $collection->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-[9px] uppercase tracking-wider text-neutral-400">No Image</div>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <p class="font-medium">{{ $collection->name }}</p>
                                    <p class="mt-1 truncate text-xs text-neutral-500">{{ $collection->slug }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center tabular-nums">{{ number_format($collection->products_count) }}</td>
                        <td class="px-5 py-4 text-center tabular-nums">{{ $collection->display_order }}</td>

                        <td class="px-5 py-4">
                            @if($collection->is_active)
                                <span class="text-xs font-medium uppercase tracking-wider text-green-700">Active</span>
                            @else
                                <span class="text-xs font-medium uppercase tracking-wider text-neutral-400">Inactive</span>
                            @endif
                        </td>

                        <td class="whitespace-nowrap px-5 py-4 text-neutral-500">{{ $collection->updated_at->format('d M Y') }}</td>

                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('admin.collections.show', $collection) }}" class="text-xs font-medium uppercase tracking-wider underline underline-offset-4">View</a>
                                <a href="{{ route('admin.collections.edit', $collection) }}" class="text-xs font-medium uppercase tracking-wider underline underline-offset-4">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <p class="font-medium">No collections yet.</p>
                            <p class="mt-1 text-sm text-neutral-500">Create your first collection to group products for the storefront.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $collections->links() }}</div>

</div>
@endsection
