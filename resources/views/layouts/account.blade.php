@extends('layouts.storefront')

@section('content')
<div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-4 lg:items-start lg:gap-12 lg:py-16">
    <aside class="sidebar-scroll lg:sticky lg:top-6 lg:col-span-1 lg:max-h-[calc(100vh-9rem)] lg:overflow-y-auto" aria-label="Account navigation">
        <div class="border-y border-neutral-200 py-5 lg:border lg:p-6">
            <h2 class="text-base font-medium">My Account</h2>
            <p class="mt-1 truncate text-sm text-neutral-500">{{ auth()->user()->name }}</p>
            <nav class="mt-5 flex gap-1 overflow-x-auto pb-2 text-sm lg:block lg:space-y-1 lg:overflow-visible lg:pb-0">
                @if(auth()->user()->hasRole('customer'))
                    <a href="{{ route('customer.dashboard') }}" class="block shrink-0 px-4 py-3 {{ request()->routeIs('customer.dashboard') ? 'bg-black text-white' : 'hover:bg-neutral-100' }}">Dashboard</a>
                    <a href="{{ route('customer.orders.index') }}" class="block shrink-0 px-4 py-3 {{ request()->routeIs('customer.orders.*') ? 'bg-black text-white' : 'hover:bg-neutral-100' }}">Orders</a>
                    <a href="{{ route('wishlist.index') }}" class="block shrink-0 px-4 py-3 {{ request()->routeIs('wishlist.*') ? 'bg-black text-white' : 'hover:bg-neutral-100' }}">Wishlist</a>
                    <a href="{{ route('customer.addresses.index') }}" class="block shrink-0 px-4 py-3 {{ request()->routeIs('customer.addresses.*') ? 'bg-black text-white' : 'hover:bg-neutral-100' }}">Addresses</a>
                    <a href="{{ route('customer.notifications.index') }}" class="flex shrink-0 items-center justify-between gap-3 px-4 py-3 {{ request()->routeIs('customer.notifications.*') ? 'bg-black text-white' : 'hover:bg-neutral-100' }}">Notifications
                        @if($unreadNotificationCount > 0)
                            <span class="text-xs" aria-label="{{ $unreadNotificationCount }} unread notifications">{{ $unreadNotificationCount }}</span>
                        @endif
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="block shrink-0 px-4 py-3 {{ request()->routeIs('profile.*') ? 'bg-black text-white' : 'hover:bg-neutral-100' }}">Profile & Security</a>
            </nav>
        </div>
    </aside>
    <div class="min-w-0 lg:col-span-3">
        @yield('account_content')
    </div>
</div>
@endsection
