@extends('layouts.guest')

@section('title', 'Create Account  19HOUSE')

@section('content')

<div class="min-h-screen flex items-center justify-center px-6 py-16">

    <div class="w-full max-w-md">

        <a
            href="{{ route('home') }}"
            class="brand-logo text-xl"
        >
            19HOUSE
        </a>

        <div class="mt-14">
            <h1 class="auth-display">
                CREATE ACCOUNT
            </h1>
        </div>

        <form
            action="{{ route('register') }}"
            method="POST"
            class="mt-10 space-y-6"
        >

            @csrf

            <div>
                <label class="block text-xs mb-2 tracking-wider">
                    NAME
                </label>

                <input
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full border border-neutral-300 bg-transparent px-4 py-3"
                >

                @error('name')
                    <p class="mt-2 text-sm text-red-700">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs mb-2 tracking-wider">
                    EMAIL
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full border border-neutral-300 bg-transparent px-4 py-3"
                >

                @error('email')
                    <p class="mt-2 text-sm text-red-700">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs mb-2 tracking-wider">
                    PHONE
                </label>

                <input
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full border border-neutral-300 bg-transparent px-4 py-3"
                >

                @error('phone')
                    <p class="mt-2 text-sm text-red-700">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs mb-2 tracking-wider">
                    PASSWORD
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full border border-neutral-300 bg-transparent px-4 py-3"
                >

                @error('password')
                    <p class="mt-2 text-sm text-red-700">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs mb-2 tracking-wider">
                    CONFIRM PASSWORD
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full border border-neutral-300 bg-transparent px-4 py-3"
                >
            </div>

            <button
                class="storefront-button w-full bg-black text-white py-4"
            >
                CREATE ACCOUNT
            </button>

        </form>

        <p class="mt-8 text-sm">
            Already have an account?

            <a
                href="{{ route('login') }}"
                class="underline underline-offset-4"
            >
                SIGN IN
            </a>
        </p>

    </div>

</div>

@endsection
