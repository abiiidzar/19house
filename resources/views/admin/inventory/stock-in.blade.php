@extends('layouts.admin')
@section('title', 'Stock In')
@section('content')
<div class="max-w-xl bg-white border p-6"><h1 class="text-2xl font-semibold mb-6">Stock In</h1><form method="POST" action="{{ route('admin.inventory.stock-in.store') }}" class="space-y-4">@csrf
@foreach(['sku' => 'SKU', 'quantity' => 'Quantity', 'reference' => 'Reference', 'note' => 'Note'] as $key => $label)<label class="block text-sm">{{ $label }}<input name="{{ $key }}" value="{{ old($key, $key === 'quantity' ? 1 : '') }}" class="mt-1 block w-full border p-3" {{ in_array($key, ['sku', 'quantity']) ? 'required' : '' }}></label>@error($key)<p class="text-red-600 text-sm">{{ $message }}</p>@enderror @endforeach
<button class="bg-neutral-900 text-white px-5 py-3">Add stock</button></form></div>
@endsection
