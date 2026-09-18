<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Management') - 19HOUSE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="operations-shell min-h-screen bg-[#F7F7F5] text-[#111111]">
    <div x-data="{ menuOpen: false, previousFocus: null, openMenu() { this.previousFocus = document.activeElement; this.menuOpen = true; this.$nextTick(() => this.$refs.menuClose.focus()) }, closeMenu() { this.menuOpen = false; this.$nextTick(() => this.previousFocus?.focus()) } }" x-effect="document.body.classList.toggle('overflow-hidden', menuOpen && window.innerWidth < 768)" x-on:keydown.escape.window="if (menuOpen) closeMenu()" class="min-h-screen md:flex">
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-neutral-800 bg-[#111111] px-5 text-white md:hidden">
            <a href="{{ route('management.dashboard') }}" class="brand-logo text-sm">19HOUSE <span class="text-neutral-500">/ Management</span></a>
            <button type="button" x-on:click="openMenu()" aria-label="Open management menu" aria-controls="management-sidebar" x-bind:aria-expanded="menuOpen.toString()" class="inline-flex h-11 w-11 items-center justify-center border border-neutral-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
        </header>

        <div x-cloak x-show="menuOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/60 backdrop-blur-[2px] md:hidden" x-on:click="closeMenu()"></div>

        <aside id="management-sidebar" x-bind:aria-hidden="(! menuOpen && window.innerWidth < 768).toString()" x-bind:inert="! menuOpen && window.innerWidth < 768" x-on:keydown.tab="if (menuOpen) window.trapDialogFocus($event, $el)" class="sidebar-scroll fixed inset-y-0 left-0 z-50 flex w-[min(20rem,88vw)] flex-col overflow-y-auto border-r border-neutral-800 bg-[#111111] p-6 text-white transition-transform duration-300 ease-out md:sticky md:top-0 md:z-auto md:h-screen md:max-h-screen md:w-64 md:translate-x-0 md:shrink-0" x-bind:class="menuOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex items-center justify-between gap-4"><a href="{{ route('management.dashboard') }}" class="brand-logo text-sm">19HOUSE <span class="text-neutral-500">/ Management</span></a><button type="button" x-ref="menuClose" x-on:click="closeMenu()" aria-label="Close management menu" class="inline-flex h-11 w-11 shrink-0 items-center justify-center border border-neutral-700 text-2xl text-neutral-400 md:hidden">&times;</button></div>
            <nav class="mt-10 space-y-1 text-sm" aria-label="Management navigation">
                <p class="ops-nav-label">Reports</p>
                <a href="{{ route('management.dashboard') }}" class="ops-nav-link w-full" @if(request()->routeIs('management.dashboard')) aria-current="page" @endif>Dashboard</a>
                <a href="{{ route('management.reports.sales') }}" class="ops-nav-link w-full" @if(request()->routeIs('management.reports.sales')) aria-current="page" @endif>Sales</a>
                <a href="{{ route('management.reports.products') }}" class="ops-nav-link w-full" @if(request()->routeIs('management.reports.products')) aria-current="page" @endif>Products</a>
                <a href="{{ route('management.reports.inventory') }}" class="ops-nav-link w-full" @if(request()->routeIs('management.reports.inventory')) aria-current="page" @endif>Inventory</a>
                <a href="{{ route('management.reports.customers') }}" class="ops-nav-link w-full" @if(request()->routeIs('management.reports.customers')) aria-current="page" @endif>Customers</a>
                <a href="{{ route('profile.edit') }}" class="ops-nav-link w-full" @if(request()->routeIs('profile.edit')) aria-current="page" @endif>Profile</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="mt-auto border-t border-neutral-800 pt-5">@csrf<button class="px-4 text-xs uppercase tracking-[0.1em] text-neutral-400 underline underline-offset-4 hover:text-white">Log out</button></form>
        </aside>
        <main class="min-w-0 flex-1 px-5 py-8 md:px-10">@yield('content')</main>
    </div>
</body>
</html>
