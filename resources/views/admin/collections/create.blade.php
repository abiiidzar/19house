@extends('layouts.admin')

@section('title', 'Create Collection')

@section('content')
<div class="max-w-4xl space-y-6">

    <div>
        <a href="{{ route('admin.collections.index') }}" class="text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-black">← Collections</a>
        <h1 class="mt-6 text-2xl font-semibold">Create Collection</h1>
        <p class="mt-1 text-sm text-neutral-500">Create a collection and choose the products included in it.</p>
    </div>

    <form method="POST" action="{{ route('admin.collections.store') }}" enctype="multipart/form-data">
        @include('admin.collections._form')
    </form>

</div>
@endsection
