<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '19HOUSE')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="storefront-shell flex min-h-screen flex-col bg-[#F7F7F5] text-[#111111]">

    {{-- Header --}}
    <x-storefront.header />

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    <div x-data="{ authOpen: false, action: 'cart', previousFocus: null, notice: '', noticeTimer: null, openAuth(action) { this.previousFocus = document.activeElement; this.action = action; this.authOpen = true; this.$nextTick(() => this.$refs.authClose.focus()) }, closeAuth() { this.authOpen = false; this.$nextTick(() => this.previousFocus?.focus()) }, showNotice(message) { this.notice = message; clearTimeout(this.noticeTimer); this.noticeTimer = setTimeout(() => this.notice = '', 4000) } }"
         x-on:auth-required.window="openAuth($event.detail.action)"
         x-on:storefront-notice.window="showNotice($event.detail.message)"
         x-on:keydown.escape.window="if (authOpen) closeAuth()"
         class="relative z-50">
        <div x-show="authOpen" x-cloak x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/45 px-5 backdrop-blur-sm"
             @click.self="closeAuth()">
            <div role="dialog" aria-modal="true" aria-labelledby="auth-dialog-title"
                 x-on:keydown.tab="window.trapDialogFocus($event, $el)"
                 class="relative w-full max-w-md bg-[#F7F7F5] px-8 py-10 text-center shadow-2xl">
                <button type="button" x-ref="authClose" @click="closeAuth()" aria-label="Tutup" class="absolute right-5 top-4 text-2xl text-neutral-500 hover:text-black">&times;</button>
                <p class="text-xs uppercase tracking-[0.24em] text-neutral-500">19HOUSE</p>
                <h2 id="auth-dialog-title" class="storefront-section-title mt-5">Masuk terlebih dahulu</h2>
                <p class="mt-4 text-sm leading-6 text-neutral-600"
                   x-text="action === 'wishlist' ? 'Masuk ke akun customer untuk menyimpan produk ke wishlist.' : action === 'checkout' ? 'Masuk ke akun customer untuk melanjutkan checkout.' : 'Masuk ke akun customer untuk menambahkan produk ke keranjang.'"></p>
                <a href="{{ route('login') }}" class="storefront-button mt-8 block bg-black px-6 py-4 text-white hover:bg-neutral-800">LOGIN</a>
                <button type="button" @click="closeAuth()" class="storefront-button mt-5 underline underline-offset-4">Nanti saja</button>
            </div>
        </div>

        <div x-show="notice" x-cloak x-transition
             role="status" aria-live="polite"
             class="fixed inset-x-4 bottom-4 z-[60] border border-neutral-300 bg-[#F7F7F5] px-5 py-4 text-sm text-[#111111] shadow-lg sm:inset-x-auto sm:bottom-6 sm:right-6 sm:max-w-sm"
             x-text="notice"></div>
    </div>

    {{-- Footer --}}
    <x-storefront.footer />

    @livewireScripts
</body>
</html>
