@extends('layouts.admin')
@section('title', 'Stock Adjustment')
@section('content')
<div class="max-w-xl bg-white border p-6"><h1 class="text-2xl font-semibold mb-6">Stock Adjustment</h1><form method="POST" action="{{ route('admin.inventory.adjustment.store') }}" class="space-y-4">@csrf
<label class="block text-sm">SKU<input name="sku" value="{{ old('sku') }}" required class="mt-1 block w-full border p-3"></label>@error('sku')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
<label class="block text-sm">Direction<select name="direction" class="mt-1 block w-full border p-3"><option value="IN">Increase</option><option value="OUT" @selected(old('direction') === 'OUT')>Decrease</option></select></label>
<label class="block text-sm">Quantity<input type="number" min="1" name="quantity" value="{{ old('quantity', 1) }}" required class="mt-1 block w-full border p-3"></label>@error('quantity')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
<label class="block text-sm">Reason<input name="reason" value="{{ old('reason') }}" required class="mt-1 block w-full border p-3"></label>@error('reason')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
<button class="bg-neutral-900 text-white px-5 py-3">Save adjustment</button></form></div>
@endsection
