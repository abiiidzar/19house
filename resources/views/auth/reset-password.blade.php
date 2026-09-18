@extends('layouts.guest')

@section('title', 'Reset Password - 19HOUSE')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-16">
    <div class="w-full max-w-md">
        <a href="{{ route('home') }}" class="brand-logo text-xl">19HOUSE</a>

        <div class="mt-14">
            <h1 class="auth-display">NEW PASSWORD</h1>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="mt-10 space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email" class="block text-xs tracking-wider mb-2">EMAIL</label>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none">
                @error('email') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs tracking-wider mb-2">PASSWORD</label>
                <input id="password" name="password" type="password" required class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none">
                @error('password') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs tracking-wider mb-2">CONFIRM PASSWORD</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none">
            </div>

            <button type="submit" class="storefront-button w-full bg-black text-white py-4 hover:bg-neutral-800 transition">
                RESET PASSWORD
            </button>
        </form>
    </div>
</div>
@endsection
