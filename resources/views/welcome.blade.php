@extends('layouts.storefront')

@section('title', 'Welcome - 19HOUSE')

@section('content')
<section class="flex min-h-[70vh] items-end border-b border-neutral-200 bg-[#111111] px-6 py-16 text-white sm:px-10 md:py-24">
    <div class="mx-auto w-full max-w-7xl">
        <p class="storefront-nav text-neutral-400">19HOUSE / Indonesia</p>
        <h1 class="storefront-hero-title mt-5 max-w-5xl">Clothes for everyday life.</h1>
        <p class="mt-6 max-w-md text-sm leading-7 text-neutral-300">Contemporary essentials shaped by culture and designed beyond the season.</p>
        <a href="{{ route('shop.index') }}" class="editorial-link mt-8 border-white">Explore the collection <span aria-hidden="true">→</span></a>
    </div>
</section>
@endsection
