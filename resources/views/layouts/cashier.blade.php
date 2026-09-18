<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cashier') - 19HOUSE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="operations-shell min-h-screen bg-[#F7F7F5] font-sans text-[#111111]">
    <div x-data="{ menuOpen: false, previousFocus: null, openMenu() { this.previousFocus = document.activeElement; this.menuOpen = true; this.$nextTick(() => this.$refs.menuClose.focus()) }, closeMenu() { this.menuOpen = false; this.$nextTick(() => this.previousFocus?.focus()) } }" x-effect="document.body.classList.toggle('overflow-hidden', menuOpen && window.innerWidth < 1024)" x-on:keydown.escape.window="if (menuOpen) closeMenu()" class="min-h-screen lg:flex">
        <header class="no-print sticky top-0 z-30 flex h-16 items-center justify-between border-b border-neutral-800 bg-[#111111] px-5 text-white lg:hidden">
            <a href="{{ route('cashier.dashboard') }}" class="brand-logo text-sm">19HOUSE <span class="text-neutral-500">/ POS</span></a>
            <button type="button" x-on:click="openMenu()" aria-label="Open cashier menu" aria-controls="cashier-sidebar" x-bind:aria-expanded="menuOpen.toString()" class="inline-flex h-11 w-11 items-center justify-center border border-neutral-700">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </header>

        <div x-cloak x-show="menuOpen" x-transition.opacity class="no-print fixed inset-0 z-40 bg-black/60 backdrop-blur-[2px] lg:hidden" x-on:click="closeMenu()"></div>

        <aside id="cashier-sidebar" x-bind:aria-hidden="(! menuOpen && window.innerWidth < 1024).toString()" x-bind:inert="! menuOpen && window.innerWidth < 1024" x-on:keydown.tab="if (menuOpen) window.trapDialogFocus($event, $el)" class="sidebar-scroll no-print fixed inset-y-0 left-0 z-50 flex w-[min(20rem,88vw)] flex-col overflow-y-auto border-r border-neutral-800 bg-[#111111] p-6 text-white transition-transform duration-300 ease-out lg:sticky lg:top-0 lg:z-auto lg:h-screen lg:max-h-screen lg:w-64 lg:translate-x-0 lg:shrink-0 lg:p-7" x-bind:class="menuOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex items-center justify-between gap-4 lg:block">
                <a href="{{ route('cashier.dashboard') }}" class="brand-logo text-sm">19HOUSE <span class="text-neutral-500">/ POS</span></a>
                <button type="button" x-ref="menuClose" x-on:click="closeMenu()" aria-label="Close cashier menu" class="inline-flex h-11 w-11 items-center justify-center border border-neutral-700 text-2xl text-neutral-400 lg:hidden">&times;</button>
            </div>
            <span class="mt-5 inline-flex items-center gap-2 text-[10px] uppercase tracking-[0.14em] text-neutral-500 lg:mt-4"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Register active</span>
            <nav class="mt-6 flex flex-wrap gap-2 text-sm lg:mt-10 lg:flex-col" aria-label="Cashier navigation">
                <p class="ops-nav-label w-full">Workspace</p>
                <a href="{{ route('cashier.dashboard') }}" class="ops-nav-link w-full" @if(request()->routeIs('cashier.dashboard')) aria-current="page" @endif>Dashboard</a>
                <a href="{{ route('cashier.pos.index') }}" class="ops-nav-link w-full" @if(request()->routeIs('cashier.pos.*')) aria-current="page" @endif>New Sale</a>
                <a href="{{ route('cashier.transactions.index') }}" class="ops-nav-link w-full" @if(request()->routeIs('cashier.transactions.*', 'cashier.receipt.*')) aria-current="page" @endif>Transactions</a>
            </nav>
            <div class="mt-7 border-t border-neutral-800 pt-5 lg:mt-auto">
                <p class="text-[10px] uppercase tracking-[0.14em] text-neutral-600">Signed in as</p>
                <p class="mt-2 text-sm text-neutral-300">{{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">@csrf<button class="text-xs uppercase tracking-[0.1em] text-neutral-500 underline underline-offset-4 hover:text-white">Logout</button></form>
            </div>
        </aside>
        <main class="min-w-0 flex-1 p-5 sm:p-7 lg:p-10 xl:p-12">@yield('content')</main>
    </div>
    @livewireScripts
</body>
</html>
