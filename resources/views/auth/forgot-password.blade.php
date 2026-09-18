@extends('layouts.guest')

@section('title', 'Forgot Password - 19HOUSE')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-16">
    <div class="w-full max-w-md">
        <a href="{{ route('home') }}" class="brand-logo text-xl">19HOUSE</a>

        <div class="mt-14">
            <h1 class="auth-display">RESET PASSWORD</h1>
            <p class="mt-3 text-sm text-neutral-500">Enter your email to receive a reset link.</p>
        </div>

        @if (session('status'))
            <div class="mt-6 border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="mt-10 space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-xs tracking-wider mb-2">EMAIL</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none">
                @error('email') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="storefront-button w-full bg-black text-white py-4 hover:bg-neutral-800 transition">
                SEND RESET LINK
            </button>
        </form>

        <p class="mt-8 text-sm">
            Remember your password? <a href="{{ route('login') }}" class="underline underline-offset-4">SIGN IN</a>
        </p>
    </div>
</div>
@endsection
