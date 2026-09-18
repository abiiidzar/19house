@extends('layouts.admin')

@section('title', 'Edit Collection')

@section('content')
<div class="max-w-4xl space-y-8">

    <div>
        <a href="{{ route('admin.collections.show', $collection) }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Collection Detail</a>
        <h1 class="mt-6 text-2xl font-semibold">Edit Collection</h1>
        <p class="mt-1 text-sm text-neutral-500">Update collection information, products, storefront image, and display order.</p>
    </div>

    <form method="POST" action="{{ route('admin.collections.update', $collection) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.collections._form')
    </form>

    {{-- PRODUCT SORTING --}}
    <section class="border-t pt-8">
        <div class="mb-5">
            <h2 class="font-semibold">Product order</h2>
            <p class="mt-1 text-sm text-neutral-500">Arrange the order products appear inside this collection.</p>
        </div>

        @livewire('admin.catalog.collection-product-sorter', ['collection' => $collection])
    </section>

</div>
@endsection
