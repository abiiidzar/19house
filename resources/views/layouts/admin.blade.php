<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - 19HOUSE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="operations-shell min-h-screen bg-[#F7F7F5] text-[#111111]">
    <div class="min-h-screen md:flex">
        <aside class="admin-sidebar sidebar-scroll border-b border-neutral-800 bg-[#111111] p-5 text-white md:sticky md:top-0 md:h-screen md:max-h-screen md:w-64 md:shrink-0 md:overflow-y-auto md:border-b-0 md:border-r md:p-6">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.dashboard') }}" class="brand-logo text-sm">19HOUSE <span class="text-neutral-500">/ Admin</span></a>
                <label for="admin-mobile-menu" class="cursor-pointer border border-neutral-700 px-4 py-2 text-xs font-medium uppercase tracking-wider md:hidden">Menu</label>
            </div>
            <input id="admin-mobile-menu" type="checkbox" class="peer sr-only" aria-label="Show admin navigation">

            <nav class="mt-5 hidden max-h-[65vh] space-y-7 overflow-y-auto border-t border-neutral-800 pt-5 text-sm peer-checked:block md:mt-10 md:block md:max-h-none md:overflow-visible md:border-0 md:pt-0" aria-label="Admin navigation">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-neutral-400">Overview</p>
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Dashboard</a>
                </div>
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-neutral-400">Fulfillment</p>
                    <a href="{{ route('admin.orders.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Orders</a>
                    <a href="{{ route('admin.cancellations.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.cancellations.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Cancellations</a>
                    <a href="{{ route('admin.payments.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.payments.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Payments</a>
                </div>
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-neutral-400">Catalog</p>
                    <a href="{{ route('admin.products.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.products.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Products</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.categories.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Categories</a>
                    <a href="{{ route('admin.collections.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.collections.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Collections</a>
                    <a href="{{ route('admin.sizes.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.sizes.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Sizes</a>
                    <a href="{{ route('admin.inventory.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.inventory.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Inventory</a>
                    <a href="{{ route('admin.homepage.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.homepage.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Homepage content</a>
                </div>
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-neutral-400">Marketing</p>
                    <a href="{{ route('admin.vouchers.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.vouchers.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Vouchers</a>
                </div>
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-neutral-400">System</p>
                    <a href="{{ route('admin.notifications.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.notifications.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Notifications</a>
                    <a href="{{ route('admin.activity-logs.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.activity-logs.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Activity log</a>
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Users</a>
                    <a href="{{ route('admin.customers.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.customers.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Customers</a>
                    <a href="{{ route('admin.roles.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.roles.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Roles & permissions</a>
                    <a href="{{ route('admin.reports.sales') }}" class="block px-4 py-3 {{ request()->routeIs('admin.reports.sales') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Sales report</a>
                    <a href="{{ route('admin.reports.products') }}" class="block px-4 py-3 {{ request()->routeIs('admin.reports.products') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Product report</a>
                    <a href="{{ route('admin.reports.inventory') }}" class="block px-4 py-3 {{ request()->routeIs('admin.reports.inventory') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Inventory report</a>
                    <a href="{{ route('admin.reports.customers') }}" class="block px-4 py-3 {{ request()->routeIs('admin.reports.customers') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Customer report</a>
                    <a href="{{ route('admin.settings.index') }}" class="block px-4 py-3 {{ request()->routeIs('admin.settings.*') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:text-black' }}">Settings</a>
                </div>
            </nav>
        </aside>

        <main class="min-w-0 flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4 border-b border-neutral-200 pb-5 sm:gap-5">
            <p class="page-kicker">Retail operations / @yield('title', 'Workspace')</p>
            <div class="flex items-center gap-5">
            <a href="{{ route('admin.notifications.index') }}" class="relative inline-flex items-center gap-2 text-sm" aria-label="Notifications">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h16M9 17a3 3 0 006 0" /></svg>
                <span>Notifications</span>
                @if($unreadNotificationCount > 0)<span class="status-badge border-error/20 bg-error/5 text-error">{{ $unreadNotificationCount }}</span>@endif
            </a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-sm underline">Logout</button></form>
            </div>
        </div>
        @if(session('success'))
            <div class="mb-6 border border-green-200 bg-green-50 p-4 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 border border-red-200 bg-red-50 p-4 text-sm">{{ session('error') }}</div>
        @endif
        @yield('content')
        </main>
    </div>
</body>
</html>
