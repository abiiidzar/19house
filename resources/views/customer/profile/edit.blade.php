@extends('layouts.account')
@section('title', 'My Profile - 19HOUSE')

@section('account_content')
    <div class="max-w-2xl">
        <div class="flex justify-between items-center border-b border-neutral-200 pb-6 mb-8">
            <h1 class="storefront-section-title">My Profile</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="storefront-button underline">LOGOUT</button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ preview: null, clearPreview() { if (this.preview) URL.revokeObjectURL(this.preview); this.preview = null } }">
            @csrf
            @method('PATCH')

            <section class="border-b border-neutral-200 pb-7" aria-labelledby="profile-photo-title">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    <div class="h-28 w-28 shrink-0 overflow-hidden rounded-full border border-neutral-300 bg-neutral-100">
                        <template x-if="preview">
                            <img :src="preview" alt="New profile photo preview" class="h-full w-full object-cover">
                        </template>
                        <template x-if="! preview">
                            @if(auth()->user()->profile_photo_path)
                                <img src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}" alt="Current profile photo for {{ auth()->user()->name }}" class="h-full w-full object-cover">
                            @else
                                <span class="flex h-full w-full items-center justify-center bg-[#111111] text-3xl font-medium text-white" aria-label="No profile photo">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                            @endif
                        </template>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h2 id="profile-photo-title" class="text-base font-medium">Profile photo</h2>
                        <p class="mt-1 text-sm leading-6 text-neutral-500">JPG, PNG, or WebP. Maximum file size 2 MB.</p>
                        <label for="profile_photo" class="mt-4 inline-flex min-h-11 cursor-pointer items-center border border-neutral-400 px-4 text-sm font-medium hover:border-black">Choose photo</label>
                        <input id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="clearPreview(); if ($event.target.files[0]) preview = URL.createObjectURL($event.target.files[0])">
                        @if(auth()->user()->profile_photo_path)
                            <label class="ml-3 inline-flex min-h-11 cursor-pointer items-center gap-2 text-sm text-error">
                                <input type="checkbox" name="remove_profile_photo" value="1" @change="if ($event.target.checked) { clearPreview(); document.getElementById('profile_photo').value = '' }">
                                Remove current photo
                            </label>
                        @endif
                        @error('profile_photo')<p class="mt-2 text-sm text-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <div>
                <label for="name" class="block text-xs tracking-wider mb-2">NAME</label>
                <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none">
                @error('name') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs tracking-wider mb-2">EMAIL</label>
                <input id="email" type="email" value="{{ auth()->user()->email }}" disabled class="w-full border border-neutral-200 bg-neutral-100 px-4 py-3 text-neutral-500 cursor-not-allowed">
            </div>

            <div>
                <label for="phone" class="block text-xs tracking-wider mb-2">PHONE</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', auth()->user()->phone) }}" class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none">
                @error('phone') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="storefront-button bg-black text-white py-4 px-5 hover:bg-neutral-800 transition">
                SAVE CHANGES
            </button>
        </form>

        <section class="mt-12 border-t border-neutral-200 pt-8" aria-labelledby="security-title">
            <h2 id="security-title" class="storefront-section-title">Security</h2>
            <p class="mt-2 text-sm text-neutral-500">Change your password using your current password.</p>
            <form method="POST" action="{{ route('profile.password.update') }}" class="mt-6 space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="current_password" class="mb-2 block text-xs uppercase tracking-[0.08em]">Current password</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" required class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none">
                    @error('current_password')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="new_password" class="mb-2 block text-xs uppercase tracking-[0.08em]">New password</label>
                    <input id="new_password" name="password" type="password" autocomplete="new-password" required class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none">
                    @error('password')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="mb-2 block text-xs uppercase tracking-[0.08em]">Confirm new password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="w-full border border-neutral-300 bg-transparent px-4 py-3 focus:border-black focus:outline-none">
                </div>
                <button type="submit" class="storefront-button bg-black px-5 py-4 text-white hover:bg-neutral-800">Update Password</button>
            </form>
        </section>
    </div>
@endsection
