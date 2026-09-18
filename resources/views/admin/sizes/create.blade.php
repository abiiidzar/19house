@extends('layouts.admin')

@section('title', 'Create Size')

@section('content')
<div class="max-w-2xl space-y-6">

    <div>
        <a href="{{ route('admin.sizes.index') }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Sizes</a>
        <h1 class="mt-6 text-2xl font-semibold">Create Size</h1>
        <p class="mt-1 text-sm text-neutral-500">Create a size that can be assigned to product SKUs.</p>
    </div>

    <form method="POST" action="{{ route('admin.sizes.store') }}">
        @include('admin.sizes._form')
    </form>

</div>
@endsection
