@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Inventory Management</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage stock ins, adjustments, and movements.</p>
        </div>
        <div class="flex flex-wrap gap-2"><a href="{{ route('admin.inventory.stock-in.create') }}" class="border bg-white px-4 py-3 text-sm">Stock in</a><a href="{{ route('admin.inventory.adjustment.create') }}" class="border bg-white px-4 py-3 text-sm">Adjust</a><a href="{{ route('admin.inventory.movements.index') }}" class="border border-neutral-300 bg-white px-4 py-3 text-sm hover:bg-neutral-50">Movements</a></div>
    </div>

    @include('admin._search', ['id' => 'inventory-search', 'placeholder' => 'Search SKU, product, variant, or size'])

    <div class="overflow-x-auto bg-white">
        <table class="w-full">
            <thead>
                <tr class="border-b text-left text-xs uppercase text-neutral-500">
                    <th class="px-5 py-4">SKU</th>
                    <th class="px-5 py-4">Product</th>
                    <th class="px-5 py-4">Variant / Size</th>
                    <th class="px-5 py-4 text-right">On Hand</th>
                    <th class="px-5 py-4 text-right">Reserved</th>
                    <th class="px-5 py-4 text-right">Available</th>
                    <th class="px-5 py-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr class="border-b">
                    <td class="px-5 py-4 font-mono text-sm">{{ $stock->sku->sku }}</td>
                    <td class="px-5 py-4 text-sm font-medium">{{ $stock->sku->product->name }}</td>
                    <td class="px-5 py-4 text-sm text-neutral-500">{{ $stock->sku->variant->name }} / {{ $stock->sku->size->name }}</td>
                    <td class="px-5 py-4 text-right font-medium tabular-nums">{{ $stock->on_hand }}</td>
                    <td class="px-5 py-4 text-right tabular-nums text-neutral-500">{{ $stock->reserved }}</td>
                    <td class="px-5 py-4 text-right font-semibold tabular-nums {{ $stock->available <= 0 ? 'text-error' : ($stock->available <= 3 ? 'text-warning' : 'text-success') }}">{{ $stock->available }}</td>
                    <td class="px-5 py-4"><span class="status-badge {{ $stock->available <= 0 ? 'text-error' : ($stock->available <= 3 ? 'text-warning' : 'text-success') }}">{{ $stock->available <= 0 ? 'Out of stock' : ($stock->available <= 3 ? 'Low stock' : 'Available') }}</span></td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-neutral-500">No stock records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $stocks->links() }}
</div>
@endsection
