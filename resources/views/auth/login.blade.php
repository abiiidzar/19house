@extends('layouts.guest')

@section('title', 'Sign In  19HOUSE')

@section('content')

<div class="min-h-screen grid lg:grid-cols-2">

    {{-- EDITORIAL IMAGE --}}
    @php($loginEditorialImage = \App\Models\Setting::valueFor('auth_login_image'))
    <div class="relative h-48 overflow-hidden bg-[#111111] sm:h-64 lg:h-auto">
        @if($loginEditorialImage)

            <img src="{{ Storage::disk('public')->url($loginEditorialImage) }}"
                 alt="{{ \App\Models\Setting::valueFor('auth_login_image_alt', '19HOUSE editorial photograph') }}"
                 class="absolute inset-0 h-full w-full object-cover">

        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>

        <div class="absolute inset-x-0 bottom-0 z-10 p-5 text-[#F7F7F5] sm:p-8 lg:p-14">
            <p class="text-xs uppercase tracking-[0.25em] text-neutral-300">19HOUSE</p>
            <p class="mt-2 max-w-md font-serif text-2xl leading-tight sm:text-3xl lg:mt-4 lg:text-5xl">Style for everyday movement.</p>
        </div>
    </div>

    {{-- LOGIN --}}
    <div class="flex items-center justify-center px-5 py-10 sm:px-6 sm:py-16">

        <div class="w-full max-w-md">

            <a
                href="{{ route('home') }}"
                class="brand-logo text-xl"
            >
                19HOUSE
            </a>

            <div class="mt-16">

                <h1 class="auth-display">
                    SIGN IN
                </h1>

                <p class="mt-3 text-sm text-neutral-500">
                    Welcome back.
                </p>

            </div>

            <form
                action="{{ route('login') }}"
                method="POST"
                class="mt-10 space-y-6"
            >

                @csrf

                <div>
                    <label
                        for="email"
                        class="block text-xs tracking-wider mb-2"
                    >
                        EMAIL
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none"
                    >

                    @error('email')
                        <p class="mt-2 text-sm text-red-700">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="password"
                        class="block text-xs tracking-wider mb-2"
                    >
                        password
                    </label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none"
                    >

                    @error('password')
                        <p class="mt-2 text-sm text-red-700">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <label class="flex items-center gap-3 text-sm">
                    <input
                        type="checkbox"
                        name="remember"
                    >
                    Remember me
                </label>

                <button
                    type="submit"
                    class="storefront-button w-full bg-black text-white py-4 hover:bg-neutral-800 transition"
                >
                    SIGN IN
                </button>

            </form>

            <p class="mt-8 text-sm">
                New to 19HOUSE?

                <a
                    href="{{ route('register') }}"
                    class="underline underline-offset-4"
                >
                    CREATE ACCOUNT
                </a>
            </p>

        </div>

    </div>

</div>

@endsection
