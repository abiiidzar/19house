<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with(['product' => function ($query) {
                $query->where('status', Product::STATUS_ACTIVE)
                    ->with('variants.images');
            }])
            ->latest()
            ->get();

        return view('customer.wishlist.index', compact('wishlists'));
    }
}
