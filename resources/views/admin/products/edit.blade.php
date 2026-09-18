@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-4xl space-y-6">

    <div>
        <a href="{{ route('admin.products.show', $product) }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Product Detail</a>
        <p class="page-kicker mt-6">Catalogue</p>
        <h1 class="mt-2 text-2xl font-semibold">Edit Product</h1>
        <p class="mt-1 text-sm text-neutral-500">Update product information, pricing, visibility, and collections.</p>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}">
        @method('PUT')
        @include('admin.products._form')
    </form>

</div>
@endsection
