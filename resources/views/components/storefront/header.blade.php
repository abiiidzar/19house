<header class="sticky top-0 z-40 border-b border-neutral-200 bg-[#F7F7F5]/95 backdrop-blur">
    <div class="relative mx-auto flex h-16 max-w-[1440px] items-center justify-between px-4 sm:px-6 lg:px-10">
        <details class="group md:hidden">
            <summary class="cursor-pointer list-none" aria-label="Open navigation">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </summary>
            <nav class="storefront-nav absolute inset-x-0 top-full max-h-[calc(100vh-4rem)] overflow-y-auto border-b border-neutral-200 bg-[#F7F7F5] px-6 py-6">
                <a class="block py-3" href="{{ route('shop.index') }}">Shop</a>
                <a class="block py-3" href="{{ route('shop.index', ['sort' => 'latest']) }}">New Arrivals</a>
                <a class="block py-3" href="{{ route('shop.index') }}#collections">Collections</a>
                <a class="block py-3" href="{{ route('home') }}#about">About</a>
                <a class="block py-3" href="{{ route('search') }}">Search</a>
                @auth
                    <a class="block py-3" href="{{ route('wishlist.index') }}">Wishlist</a>
                    <a class="block py-3" href="{{ auth()->user()->hasRole('customer') ? route('customer.dashboard') : auth()->user()->dashboardRoute() }}">Account</a>
                @endauth
                @guest
                    <a class="block py-3" href="{{ route('login') }}">Login</a>
                @endguest
            </nav>
        </details>

        <a href="{{ route('home') }}" class="brand-logo absolute left-1/2 -translate-x-1/2 text-lg sm:text-xl">{{ \App\Models\Setting::valueFor('store_name', '19HOUSE') }}</a>

        <nav class="storefront-nav hidden items-center gap-8 md:flex">
            <a href="{{ route('shop.index') }}" class="hover:text-neutral-500">Shop</a>
            <a href="{{ route('shop.index', ['sort' => 'latest']) }}" class="hover:text-neutral-500">New</a>
            <a href="{{ route('shop.index') }}#collections" class="hover:text-neutral-500">Collections</a>
            <a href="{{ route('home') }}#about" class="hover:text-neutral-500">About</a>
        </nav>

        <div class="flex items-center gap-4">
            <a href="{{ route('search') }}" aria-label="Search" class="hover:text-neutral-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </a>
            <livewire:storefront.cart-drawer />
            @auth
                @if(auth()->user()->hasRole('customer'))
                    <a href="{{ route('wishlist.index') }}" aria-label="Wishlist" class="hidden hover:text-neutral-500 sm:block">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z" /></svg>
                    </a>
                @endif
                <details class="group relative">
                    <summary class="flex cursor-pointer list-none items-center rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black"
                             aria-label="Buka menu akun {{ auth()->user()->name }}">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}"
                                 alt="Foto profil {{ auth()->user()->name }}"
                                 class="h-9 w-9 rounded-full object-cover ring-1 ring-neutral-300">
                        @else
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#111111] text-xs font-medium text-white"
                                  aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                    </summary>
                    <div class="absolute right-0 top-full z-50 mt-3 w-48 border border-neutral-200 bg-[#F7F7F5] py-2 shadow-lg">
                        <p class="truncate border-b border-neutral-200 px-4 py-2 text-xs text-neutral-500">{{ auth()->user()->name }}</p>
                        @if(auth()->user()->hasRole('customer'))
                            <a href="{{ route('customer.dashboard') }}" class="storefront-nav block px-4 py-3 hover:bg-neutral-100">My Account</a>
                            <a href="{{ route('customer.orders.index') }}" class="storefront-nav block px-4 py-3 hover:bg-neutral-100">My Orders</a>
                        @endif
                        @if(auth()->user()->hasAnyRole(['admin', 'cashier', 'management']))
                            <a href="{{ auth()->user()->dashboardRoute() }}" class="storefront-nav block px-4 py-3 hover:bg-neutral-100">Dashboard</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="storefront-nav block px-4 py-3 hover:bg-neutral-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="storefront-nav w-full px-4 py-3 text-left hover:bg-neutral-100">Logout</button>
                        </form>
                    </div>
                </details>
            @else
                <a href="{{ route('login') }}" class="storefront-nav hidden hover:text-neutral-500 md:block">Login</a>
            @endauth
        </div>
    </div>
</header>
