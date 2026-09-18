@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-4xl space-y-6">

    <div>
        <a href="{{ route('admin.categories.index') }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Categories</a>
        <h1 class="mt-6 text-2xl font-semibold">Edit Category</h1>
        <p class="mt-1 text-sm text-neutral-500">Update category information, storefront image, order, and visibility.</p>
    </div>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.categories._form')
    </form>

</div>
@endsection
