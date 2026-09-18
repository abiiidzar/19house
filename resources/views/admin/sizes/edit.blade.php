@extends('layouts.admin')

@section('title', 'Edit Size')

@section('content')
<div class="max-w-2xl space-y-6">

    <div>
        <a href="{{ route('admin.sizes.index') }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Sizes</a>
        <h1 class="mt-6 text-2xl font-semibold">Edit Size</h1>
        <p class="mt-1 text-sm text-neutral-500">Update size information and availability.</p>
    </div>

    @if($size->skus->isNotEmpty())
        <div class="border bg-white p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium">Size currently in use</p>
                    <p class="mt-1 text-xs text-neutral-500">Changes to this size will affect SKUs that currently reference it.</p>
                </div>

                <div class="text-right">
                    <p class="text-xl font-semibold">{{ $size->skus->count() }}</p>
                    <p class="text-xs text-neutral-500">{{ Str::plural('SKU', $size->skus->count()) }}</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.sizes.update', $size) }}">
        @method('PUT')
        @include('admin.sizes._form')
    </form>

</div>
@endsection
