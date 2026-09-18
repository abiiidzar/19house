@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="max-w-4xl space-y-6">

    <div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Products</a>
        <p class="page-kicker mt-6">Catalogue</p>
        <h1 class="mt-2 text-2xl font-semibold">Create Product</h1>
        <p class="mt-1 text-sm text-neutral-500">Create the basic product information before configuring variants, images, and SKUs.</p>
    </div>

    <div class="border border-neutral-200 bg-neutral-50 p-4">
        <p class="text-sm font-medium">Variants, images & SKUs</p>
        <p class="mt-1 text-sm text-neutral-500">Save the product first. Colors, product images, sizes, and SKUs can be configured from the product detail page afterward.</p>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}">
        @include('admin.products._form')
    </form>

</div>
@endsection
