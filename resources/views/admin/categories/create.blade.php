@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<div class="max-w-4xl space-y-6">

    <div>
        <a href="{{ route('admin.categories.index') }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Categories</a>
        <h1 class="mt-6 text-2xl font-semibold">Create Category</h1>
        <p class="mt-1 text-sm text-neutral-500">Create a category to organize products across the storefront.</p>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
        @include('admin.categories._form')
    </form>

</div>
@endsection
