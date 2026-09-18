<footer class="mt-20 bg-[#111111] text-[#F7F7F5]">
    <div class="border-b border-neutral-800">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 py-14 md:grid-cols-2 md:items-end lg:px-10">
            <div>
                <p class="storefront-nav text-neutral-500">Newsletter / 19HOUSE Notes</p>
                <h2 class="editorial-text mt-4 max-w-xl text-4xl leading-none sm:text-5xl">Stay close to what comes next.</h2>
            </div>
            <div class="md:text-right">
                @if($supportEmail = \App\Models\Setting::valueFor('support_email'))
                    <a href="mailto:{{ $supportEmail }}?subject=19HOUSE%20Newsletter" class="editorial-link border-neutral-500 text-neutral-200 hover:border-white hover:text-white">Join the list <span aria-hidden="true">→</span></a>
                @else
                    <p class="text-sm text-neutral-500">Newsletter registration is coming soon.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-10 px-6 py-14 md:grid-cols-4 lg:px-10">
        <div class="col-span-2 md:col-span-1">
            <h3 class="brand-logo text-xl">{{ \App\Models\Setting::valueFor('store_name', '19HOUSE') }}</h3>
            <p class="mt-4 max-w-xs text-sm leading-6 text-neutral-400">Everyday pieces, shaped by culture and made beyond the season.</p>
            @if($supportEmail = \App\Models\Setting::valueFor('support_email'))<a class="mt-3 block text-sm underline" href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>@endif
            @if($supportPhone = \App\Models\Setting::valueFor('support_phone'))<p class="mt-1 text-sm">{{ $supportPhone }}</p>@endif
            @if($storeAddress = \App\Models\Setting::valueFor('store_address'))<p class="mt-1 text-sm text-neutral-400">{{ $storeAddress }}</p>@endif
        </div>
        <div>
            <h4 class="storefront-nav text-neutral-500 mb-4">Shop</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('shop.index', ['sort' => 'latest']) }}" class="hover:underline">New Arrivals</a></li>
                <li><a href="{{ route('shop.index') }}" class="hover:underline">Collections</a></li>
            </ul>
        </div>
        <div>
            <h4 class="storefront-nav text-neutral-500 mb-4">Help</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('shipping') }}" class="hover:underline">Shipping</a></li>
                <li><a href="{{ route('returns') }}" class="hover:underline">Returns</a></li>
            </ul>
        </div>
        <div>
            <h4 class="storefront-nav mb-4 text-neutral-500">Information</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}#about" class="hover:underline">About 19HOUSE</a></li>
                @auth<li><a href="{{ route('profile.edit') }}" class="hover:underline">My Account</a></li>@endauth
                @guest<li><a href="{{ route('login') }}" class="hover:underline">Account</a></li>@endguest
            </ul>
        </div>
    </div>
    <div class="overflow-hidden border-t border-neutral-800 px-6 pt-10 lg:px-10">
        <p class="brand-logo select-none whitespace-nowrap text-center text-[18vw] leading-[0.72] tracking-[-0.06em] text-[#F7F7F5]">19HOUSE</p>
        <div class="mx-auto flex max-w-7xl flex-col gap-2 py-6 text-[10px] uppercase tracking-[0.12em] text-neutral-500 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::valueFor('store_name', '19HOUSE') }}. All rights reserved.</p>
            <p>Indonesia</p>
        </div>
    </div>
</footer>
