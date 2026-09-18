@extends('layouts.admin')
@section('title', 'Inventory Movements')
@section('content')
<div class="space-y-6"><div><a class="text-sm underline" href="{{ route('admin.inventory.index') }}">← Inventory</a><h1 class="mt-3 text-2xl font-semibold">Inventory movements</h1></div>
@include('admin._search', ['id' => 'movement-search', 'placeholder' => 'Search SKU, product, type, reference, or actor'])
<div class="overflow-x-auto border bg-white"><table class="w-full text-left text-sm"><thead><tr class="border-b"><th class="p-4">Date</th><th class="p-4">SKU</th><th class="p-4">Type</th><th class="p-4">Quantity</th><th class="p-4">Before → After</th><th class="p-4">Reference / reason</th><th class="p-4">Actor</th></tr></thead><tbody>
@forelse($movements as $movement)<tr class="border-b"><td class="p-4">{{ $movement->created_at?->format('d M Y H:i') }}</td><td class="p-4">{{ $movement->sku?->sku ?? 'Deleted SKU' }}</td><td class="p-4">{{ $movement->type }}</td><td class="p-4">{{ $movement->quantity }}</td><td class="p-4">{{ $movement->before }} → {{ $movement->after }}</td><td class="p-4">{{ $movement->reference ?? $movement->reason ?? '—' }}</td><td class="p-4">{{ $movement->actor?->name ?? 'System' }}</td></tr>@empty<tr><td colspan="7" class="p-6 text-center">No movements yet.</td></tr>@endforelse
</tbody></table></div>{{ $movements->links() }}</div>
@endsection
