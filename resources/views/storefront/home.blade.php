{{-- resources/views/storefront/home.blade.php --}}

@extends('layouts.storefront')

@section('title', '19HOUSE - Editorial Fashion')

@section('content')

{{-- =========================================================
    HERO
========================================================= --}}
<section
    data-home-motion
    class="relative flex min-h-[78vh] items-end overflow-hidden bg-neutral-200 md:min-h-[92vh]"
>
    @if($hero?->image_path)

        <picture class="home-hero-media absolute inset-0">

            @if($hero->mobile_image_path)
                <source
                    media="(max-width: 767px)"
                    srcset="{{ Storage::disk('public')->url($hero->mobile_image_path) }}"
                >
            @endif

            <img
                src="{{ Storage::disk('public')->url($hero->image_path) }}"
                alt="{{ $hero->title ?? '19HOUSE' }}"
                fetchpriority="high"
                class="h-full w-full object-cover"
            >

        </picture>

        <div
            class="absolute inset-0
                {{ $hero->text_color === 'dark'
                    ? 'bg-gradient-to-t from-white/75 via-white/5 to-transparent'
                    : 'bg-gradient-to-t from-black/70 via-black/5 to-transparent'
                }}"
        ></div>

    @endif


    <div
        class="home-hero-copy relative mx-auto w-full max-w-7xl px-6 pb-14 pt-32 md:pb-20 lg:pb-24
            {{ ($hero?->text_position ?? 'left') === 'center'
                ? 'text-center'
                : 'text-left'
            }}
            {{ $hero?->image_path && $hero->text_color === 'light'
                ? 'text-white'
                : 'text-neutral-900'
            }}"
    >

        @if($hero?->eyebrow)
            <p class="storefront-nav mb-4">
                {{ $hero->eyebrow }}
            </p>
        @else
            <p class="storefront-nav mb-4">
                19HOUSE / New Season
            </p>
        @endif


        <h1 class="storefront-hero-title">
            {{ $hero?->title ?? 'SS24 COLLECTION' }}
        </h1>


        @if($hero?->subtitle || ! $hero)
            <p
                class="mt-5 max-w-md text-sm leading-6
                    {{ ($hero?->text_position ?? 'left') === 'center'
                        ? 'mx-auto'
                        : ''
                    }}"
            >
                {{ $hero?->subtitle ?? 'Contemporary essentials made for everyday rotation.' }}
            </p>
        @endif


        @if(! $hero || ($hero->button_label && $hero->button_path))
            <a
                href="{{ $hero?->button_path ?? route('shop.index') }}"
                class="storefront-button mt-8
                    {{ $hero?->image_path && $hero->text_color === 'light'
                        ? 'border-b border-white text-white'
                        : 'border-b border-neutral-900 text-neutral-900'
                    }}"
            >
                {{ $hero?->button_label ?? 'Shop Collection' }}

                <span class="ml-2">
                    →
                </span>
            </a>
        @endif

    </div>


    {{-- HERO INDEX --}}
    <div
        class="absolute bottom-6 right-6 hidden text-[10px] uppercase tracking-[0.2em] md:block
            {{ $hero?->image_path && $hero->text_color === 'light'
                ? 'text-white/70'
                : 'text-neutral-500'
            }}"
    >
        19H / {{ date('Y') }}
    </div>

</section>


{{-- =========================================================
    BRAND STRIP
========================================================= --}}
<section data-reveal class="border-y border-neutral-200 bg-[#F7F7F5]">

    <div
        class="mx-auto flex max-w-7xl items-center gap-6 overflow-hidden px-6 py-5"
    >

        <p class="brand-logo shrink-0 text-xs">
            19HOUSE
        </p>

        <div class="hidden h-px flex-1 bg-neutral-300 md:block"></div>

        <p
            class="storefront-nav whitespace-nowrap text-[10px] text-neutral-500 md:text-xs"
        >
            Everyday Pieces / Modern Essentials / Built Beyond Seasons
        </p>

    </div>

</section>


{{-- =========================================================
    BRAND INTRODUCTION
========================================================= --}}
<section data-reveal class="border-b border-neutral-200 bg-[#F7F7F5]">
    <div class="mx-auto grid max-w-7xl gap-8 px-6 py-20 md:grid-cols-[0.7fr_1.3fr] md:py-28">
        <div>
            <p class="storefront-nav text-neutral-500">Brand Introduction</p>
            <p class="mt-4 text-xs uppercase tracking-[0.16em] text-neutral-400">Padang / Indonesia</p>
        </div>
        <div class="max-w-3xl">
            <h2 class="editorial-text text-4xl leading-[1.04] tracking-[-0.025em] sm:text-5xl lg:text-6xl">
                Everyday clothing shaped through proportion, function, and quiet expression.
            </h2>
            <p class="mt-7 max-w-xl text-sm leading-7 text-neutral-600">
                19HOUSE creates considered pieces for daily rotation—designed to feel relevant beyond a single season.
            </p>
            <a href="#about" class="editorial-link mt-8">About 19HOUSE <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>


{{-- =========================================================
    NEW ARRIVALS
========================================================= --}}
<section data-reveal class="mx-auto max-w-7xl px-6 py-20 md:py-28">

    <div
        class="mb-10 grid gap-6 border-b border-neutral-200 pb-6 md:grid-cols-2"
    >

        <div>

            <p class="storefront-nav mb-3 text-neutral-500">
                01 / Latest Drop
            </p>

            <h2 class="storefront-section-title">
                New Arrivals
            </h2>

        </div>


        <div class="flex items-end justify-between gap-6 md:justify-end">

            <p
                class="hidden max-w-xs text-sm leading-6 text-neutral-500 lg:block"
            >
                The latest pieces from 19HOUSE.
                Designed for everyday rotation and made
                to move beyond the season.
            </p>

            <a
                href="{{ route('shop.index', ['sort' => 'latest']) }}"
                class="storefront-button shrink-0 border-b border-neutral-900 pb-1"
            >
                View All
                <span class="ml-2">→</span>
            </a>

        </div>

    </div>


    <div
        class="grid grid-cols-2 gap-x-3 gap-y-10 sm:gap-x-6 md:grid-cols-4"
    >

        @forelse($newArrivals as $product)

            <div data-reveal-item style="--reveal-delay: {{ $loop->index * 70 }}ms">
                <x-storefront.product-card :product="$product" />
            </div>

        @empty

            <div
                class="col-span-full flex min-h-72 items-center justify-center border-y border-neutral-200"
            >

                <div class="text-center">

                    <p class="storefront-nav text-neutral-400">
                        New Arrivals
                    </p>

                    <p class="mt-3 text-sm text-neutral-500">
                        New pieces are coming soon.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</section>


{{-- =========================================================
    CATEGORY / DISCOVERY
    Uses active product categories that already exist
========================================================= --}}
@php
    $homeCategories = \App\Models\Category::query()
        ->where('is_active', true)
        ->orderBy('display_order')
        ->limit(4)
        ->get();
@endphp

@if($homeCategories->isNotEmpty())

<section data-reveal class="bg-[#111111] py-20 text-white md:py-28">

    <div class="mx-auto max-w-7xl px-6">

        <div
            class="mb-10 flex items-end justify-between gap-6 border-b border-neutral-800 pb-6"
        >

            <div>

                <p class="storefront-nav mb-3 text-neutral-500">
                    02 / Explore
                </p>

                <h2 class="storefront-section-title">
                    Shop by Category
                </h2>

            </div>

            <p
                class="hidden text-[10px] uppercase tracking-[0.2em] text-neutral-600 md:block"
            >
                19HOUSE / Catalog
            </p>

        </div>


        <div class="grid gap-px overflow-hidden bg-neutral-800 md:grid-cols-2">

            @foreach($homeCategories as $category)

                <a
                    href="{{ route('shop.index', ['category' => $category->slug]) }}"
                    class="group relative flex min-h-[24rem] items-end overflow-hidden bg-neutral-900 md:min-h-[34rem]"
                >

                    @if($category->image)

                        <img
                            src="{{ Storage::disk('public')->url($category->image) }}"
                            alt="{{ $category->name }}"
                            loading="lazy"
                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.02]"
                        >

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"
                        ></div>

                    @else

                        <div class="absolute inset-0 {{ $loop->even ? 'bg-[#181818]' : 'bg-[#202020]' }}"></div>

                    @endif


                    <div
                        class="relative flex w-full items-end justify-between gap-6 p-6 md:p-8"
                    >

                        <div>

                            <p
                                class="mb-3 text-[10px] uppercase tracking-[0.2em] text-neutral-400"
                            >
                                Category
                            </p>

                            <h3
                                class="font-display text-4xl leading-none md:text-5xl"
                            >
                                {{ $category->name }}
                            </h3>

                        </div>

                        <span
                            class="text-2xl transition-transform duration-300 group-hover:translate-x-1"
                        >
                            →
                        </span>

                    </div>

                </a>

            @endforeach

        </div>

    </div>

</section>

@endif


{{-- =========================================================
    MAIN EDITORIAL CAMPAIGN
========================================================= --}}
@if($editorials->isNotEmpty())

    @php
        $mainEditorial = $editorials->first();
    @endphp

    <section
        data-reveal
        class="relative flex min-h-[70vh] items-end overflow-hidden bg-neutral-900 md:min-h-[90vh]"
        aria-label="Editorial campaign"
    >

        <picture class="absolute inset-0">

            @if($mainEditorial->mobile_image_path)
                <source
                    media="(max-width: 767px)"
                    srcset="{{ Storage::disk('public')->url($mainEditorial->mobile_image_path) }}"
                >
            @endif

            <img
                src="{{ Storage::disk('public')->url($mainEditorial->image_path) }}"
                alt="{{ $mainEditorial->title }}"
                loading="lazy"
                class="h-full w-full object-cover"
            >

        </picture>


        <div
            class="absolute inset-0
                {{ $mainEditorial->text_color === 'dark'
                    ? 'bg-gradient-to-t from-white/80 via-white/5 to-transparent'
                    : 'bg-gradient-to-t from-black/75 via-black/5 to-transparent'
                }}"
        ></div>


        <div
            class="relative mx-auto w-full max-w-7xl px-6 py-16 md:py-24
                {{ $mainEditorial->text_position === 'center'
                    ? 'text-center'
                    : 'text-left'
                }}
                {{ $mainEditorial->text_color === 'light'
                    ? 'text-white'
                    : 'text-neutral-900'
                }}"
        >

            <div
                class="max-w-3xl
                    {{ $mainEditorial->text_position === 'center'
                        ? 'mx-auto'
                        : ''
                    }}"
            >

                @if($mainEditorial->eyebrow)
                    <p class="storefront-nav mb-4">
                        {{ $mainEditorial->eyebrow }}
                    </p>
                @else
                    <p class="storefront-nav mb-4">
                        Editorial / Campaign
                    </p>
                @endif


                <h2 class="storefront-section-title">
                    {{ $mainEditorial->title }}
                </h2>


                @if($mainEditorial->subtitle)

                    <p
                        class="mt-5 max-w-lg text-sm leading-6
                            {{ $mainEditorial->text_position === 'center'
                                ? 'mx-auto'
                                : ''
                            }}"
                    >
                        {{ $mainEditorial->subtitle }}
                    </p>

                @endif


                @if($mainEditorial->button_label && $mainEditorial->button_path)

                    <a
                        href="{{ $mainEditorial->button_path }}"
                        class="storefront-button mt-8 border-b pb-1"
                    >
                        {{ $mainEditorial->button_label }}
                        <span class="ml-2">→</span>
                    </a>

                @endif

            </div>

        </div>

    </section>

@else

    {{-- EDITORIAL FALLBACK --}}
    <section
        class="flex min-h-[60vh] items-center bg-[#111111] px-6 py-24 text-white"
    >

        <div class="mx-auto w-full max-w-7xl">

            <div class="max-w-4xl">

                <p class="storefront-nav text-neutral-500">
                    03 / Editorial Campaign
                </p>

                <h2
                    class="font-display mt-8 text-5xl leading-[0.95] tracking-[-0.03em] md:text-7xl lg:text-8xl"
                >
                    Crafted for the
                    modern wardrobe.
                    Designed beyond
                    seasons.
                </h2>

                <a
                    href="{{ route('shop.index') }}"
                    class="storefront-button mt-10 border-b border-white pb-1"
                >
                    Discover 19HOUSE
                    <span class="ml-2">→</span>
                </a>

            </div>

        </div>

    </section>

@endif


{{-- =========================================================
    FEATURED COLLECTION
========================================================= --}}
@if($featuredCollection)

<section data-reveal class="mx-auto max-w-7xl px-6 py-20 md:py-28">

    <div
        class="mb-10 flex flex-wrap items-end justify-between gap-6 border-b border-neutral-200 pb-6"
    >

        <div>

            <p class="storefront-nav mb-3 text-neutral-500">
                04 / Featured Collection
            </p>

            <h2 class="storefront-section-title">
                {{ $featuredCollection->name }}
            </h2>

        </div>


        <a
            href="{{ route('collections.show', $featuredCollection->slug) }}"
            class="storefront-button border-b border-neutral-900 pb-1"
        >
            Explore
            <span class="ml-2">→</span>
        </a>

    </div>


    <div
        class="grid gap-8
            {{ $featuredCollection->image
                ? 'lg:grid-cols-2'
                : ''
            }}"
    >

        @if($featuredCollection->image)

            <a
                href="{{ route('collections.show', $featuredCollection->slug) }}"
                class="group relative min-h-[34rem] overflow-hidden bg-neutral-200 lg:min-h-[52rem]"
            >

                <img
                    src="{{ Storage::disk('public')->url($featuredCollection->image) }}"
                    alt="{{ $featuredCollection->name }}"
                    loading="lazy"
                    class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.015]"
                >

                <div
                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-8 pt-24 text-white"
                >

                    <p class="storefront-nav text-white/70">
                        Collection
                    </p>

                    <p
                        class="font-display mt-2 text-4xl leading-none md:text-5xl"
                    >
                        {{ $featuredCollection->name }}
                    </p>

                </div>

            </a>

        @endif


        <div
            class="grid grid-cols-2 gap-x-3 gap-y-10 sm:gap-x-6"
        >

            @forelse($featuredCollection->products->take(4) as $product)

                <div data-reveal-item style="--reveal-delay: {{ $loop->index * 70 }}ms">
                    <x-storefront.product-card :product="$product" />
                </div>

            @empty

                <div
                    class="col-span-2 flex min-h-80 items-center justify-center border-y border-neutral-200"
                >
                    <p class="text-sm text-neutral-500">
                        Collection products coming soon.
                    </p>
                </div>

            @endforelse

        </div>

    </div>

</section>

@endif


{{-- =========================================================
    SECONDARY EDITORIAL / LOOKBOOK
========================================================= --}}
@if($editorials->count() > 1)

<section data-reveal class="mx-auto max-w-7xl px-6 pb-20 md:pb-28">

    <div class="mb-10 flex items-end justify-between gap-6 border-b border-neutral-200 pb-6">
        <div>
            <p class="storefront-nav mb-3 text-neutral-500">05 / Lookbook</p>
            <h2 class="storefront-section-title">In Context</h2>
        </div>
        <p class="hidden max-w-xs text-right text-sm leading-6 text-neutral-500 md:block">Campaign studies, silhouettes, and pieces in motion.</p>
    </div>

    <div class="grid gap-5 md:grid-cols-2">

        @foreach($editorials->skip(1)->take(2) as $editorial)

            <article
                class="group relative flex min-h-[32rem] items-end overflow-hidden bg-neutral-200 p-7 md:min-h-[42rem] md:p-10"
            >

                <picture class="absolute inset-0">

                    @if($editorial->mobile_image_path)
                        <source
                            media="(max-width: 767px)"
                            srcset="{{ Storage::disk('public')->url($editorial->mobile_image_path) }}"
                        >
                    @endif

                    <img
                        src="{{ Storage::disk('public')->url($editorial->image_path) }}"
                        alt="{{ $editorial->title }}"
                        loading="lazy"
                        class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.015]"
                    >

                </picture>


                <div
                    class="absolute inset-0
                        {{ $editorial->text_color === 'dark'
                            ? 'bg-gradient-to-t from-white/80 via-white/5 to-transparent'
                            : 'bg-gradient-to-t from-black/70 via-black/5 to-transparent'
                        }}"
                ></div>


                <div
                    class="relative w-full
                        {{ $editorial->text_position === 'center'
                            ? 'text-center'
                            : 'text-left'
                        }}
                        {{ $editorial->text_color === 'light'
                            ? 'text-white'
                            : 'text-neutral-900'
                        }}"
                >

                    @if($editorial->eyebrow)

                        <p class="storefront-nav">
                            {{ $editorial->eyebrow }}
                        </p>

                    @endif


                    <h2
                        class="font-display mt-3 text-4xl leading-none md:text-5xl"
                    >
                        {{ $editorial->title }}
                    </h2>


                    @if($editorial->subtitle)

                        <p class="mt-3 text-sm">
                            {{ $editorial->subtitle }}
                        </p>

                    @endif


                    @if($editorial->button_label && $editorial->button_path)

                        <a
                            href="{{ $editorial->button_path }}"
                            class="storefront-button mt-6 border-b pb-1"
                        >
                            {{ $editorial->button_label }}
                            <span class="ml-2">→</span>
                        </a>

                    @endif

                </div>

            </article>

        @endforeach

    </div>

</section>

@endif


{{-- =========================================================
    DETAILS MATTER
    Uses one real, image-backed catalogue product.
========================================================= --}}
@if($detailProduct && $detailImage)
<section data-reveal class="border-y border-neutral-200 bg-[#F7F7F5]">
    <div class="mx-auto grid max-w-7xl lg:grid-cols-2">
        <a href="{{ route('products.show', $detailProduct) }}" class="group relative aspect-[4/5] overflow-hidden bg-[#EFEFEC] lg:aspect-auto lg:min-h-[46rem]">
            <img
                src="{{ Storage::disk('public')->url($detailImage->path) }}"
                alt="Detail view of {{ $detailProduct->name }}"
                loading="lazy"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.015]"
            >
        </a>

        <div class="flex items-center px-6 py-16 sm:px-10 lg:px-16 lg:py-24">
            <div class="max-w-lg">
                <p class="storefront-nav text-neutral-500">06 / Details Matter</p>
                <h2 class="storefront-section-title mt-5">Made to be worn, again and again.</h2>
                <div class="mt-9 border-y border-neutral-300 py-6">
                    <p class="text-lg font-medium">{{ $detailProduct->name }}</p>
                    @if($detailProduct->description)
                        <p class="mt-4 text-sm leading-7 text-neutral-600">{{ Str::limit(preg_replace('/\s+/', ' ', $detailProduct->description), 220) }}</p>
                    @endif
                    <p class="mt-5 text-base">Rp {{ number_format($detailPrice, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('products.show', $detailProduct) }}" class="editorial-link mt-8">View the piece <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </div>
</section>
@endif


{{-- =========================================================
    SELECTED PRODUCTS
========================================================= --}}
<section data-reveal class="bg-white">

    <div class="mx-auto max-w-7xl px-6 py-20 md:py-28">

        <div
            class="mb-10 grid gap-6 border-b border-neutral-200 pb-6 md:grid-cols-2"
        >

            <div>

                <p class="storefront-nav mb-3 text-neutral-500">
                    07 / Curated
                </p>

                <h2 class="storefront-section-title">
                    Selected Products
                </h2>

            </div>


            <div class="flex items-end justify-between gap-6 md:justify-end">

                <p
                    class="hidden max-w-xs text-sm leading-6 text-neutral-500 lg:block"
                >
                    A considered selection of pieces
                    from the current 19HOUSE catalog.
                </p>

                <a
                    href="{{ route('shop.index') }}"
                    class="storefront-button shrink-0 border-b border-neutral-900 pb-1"
                >
                    Shop All
                    <span class="ml-2">→</span>
                </a>

            </div>

        </div>


        <div
            class="grid grid-cols-2 gap-x-3 gap-y-10 sm:gap-x-6 md:grid-cols-4"
        >

            @forelse($featured as $product)

                <div data-reveal-item style="--reveal-delay: {{ $loop->index * 70 }}ms">
                    <x-storefront.product-card :product="$product" />
                </div>

            @empty

                <div
                    class="col-span-full flex min-h-72 items-center justify-center"
                >

                    <p class="text-sm text-neutral-500">
                        Selected products coming soon.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>


{{-- =========================================================
    BRAND MANIFESTO
========================================================= --}}
<section
    id="about"
    data-reveal
    class="scroll-mt-20 border-y border-neutral-200 bg-[#F7F7F5]"
>

    <div
        class="mx-auto max-w-7xl px-6 py-24 text-center md:py-36"
    >

        <p class="storefront-nav text-neutral-500">
            08 / About 19HOUSE
        </p>


        <h2
            class="font-display mx-auto mt-8 max-w-5xl text-4xl leading-[1.02] tracking-[-0.025em] md:text-6xl lg:text-7xl"
        >
            Clothes for everyday life,
            shaped by culture and made
            beyond the season.
        </h2>


        <p
            class="mx-auto mt-8 max-w-xl text-sm leading-7 text-neutral-500"
        >
            19HOUSE explores contemporary essentials
            through proportion, function and everyday
            expression. Pieces designed to stay relevant
            beyond a single moment.
        </p>


        <a
            href="{{ route('shop.index') }}"
            class="storefront-button mt-10 border-b border-neutral-900 pb-1"
        >
            Explore the collection
            <span class="ml-2">→</span>
        </a>

    </div>

</section>


{{-- =========================================================
    SERVICE BENEFITS
========================================================= --}}
<section data-reveal class="bg-[#F7F7F5]">

    <div
        class="mx-auto grid max-w-7xl grid-cols-2 px-6 md:grid-cols-4"
    >

        {{-- SECURE PAYMENT --}}
        <div
            class="border-b border-r border-neutral-200 py-8 pr-5 md:border-b-0"
        >

            <span class="mb-5 block text-lg">
                01
            </span>

            <p class="storefront-nav">
                Secure Payment
            </p>

            <p class="mt-3 max-w-[12rem] text-xs leading-5 text-neutral-500">
                Safe and protected checkout for every order.
            </p>

        </div>


        {{-- DELIVERY --}}
        <div
            class="border-b border-neutral-200 py-8 pl-5 md:border-b-0 md:border-r md:px-5"
        >

            <span class="mb-5 block text-lg">
                02
            </span>

            <p class="storefront-nav">
                Delivery
            </p>

            <p class="mt-3 max-w-[12rem] text-xs leading-5 text-neutral-500">
                Shipping available across Indonesia.
            </p>

        </div>


        {{-- SUPPORT --}}
        <div
            class="border-r border-neutral-200 py-8 pr-5 md:px-5"
        >

            <span class="mb-5 block text-lg">
                03
            </span>

            <p class="storefront-nav">
                Customer Support
            </p>

            <p class="mt-3 max-w-[12rem] text-xs leading-5 text-neutral-500">
                Support when you need help with your order.
            </p>

        </div>


        {{-- ORIGINAL --}}
        <div class="py-8 pl-5">

            <span class="mb-5 block text-lg">
                04
            </span>

            <p class="storefront-nav">
                19HOUSE Pieces
            </p>

            <p class="mt-3 max-w-[12rem] text-xs leading-5 text-neutral-500">
                Curated pieces made for everyday rotation.
            </p>

        </div>

    </div>

</section>

@endsection
