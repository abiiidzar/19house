@extends('layouts.account')
@section('title', 'Wishlist - 19HOUSE')

@section('account_content')
<section>
    <h1 class="storefront-section-title mb-8 border-b border-neutral-200 pb-4">My Wishlist</h1>

    @if($wishlists->isEmpty())
        <div class="text-center py-20 text-neutral-500">
            <p>Your wishlist is empty.</p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($wishlists as $wishlist)
                <x-storefront.product-card :product="$wishlist->product" />
            @endforeach
        </div>
    @endif
</section>
@endsection
