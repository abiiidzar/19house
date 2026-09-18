<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        // Batasi hanya produk ACTIVE
        if ($product->status !== Product::STATUS_ACTIVE) {
            abort(404);
        }

        $product->load(['variants.images', 'variants.skus.size', 'variants.skus.stock', 'skus.stock', 'category']);

        // Related produk juga harus ACTIVE
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', Product::STATUS_ACTIVE)
            ->with(['category', 'variants.images', 'variants.skus.stock', 'skus.stock'])
            ->take(4)->get();

        return view('storefront.products.show', compact('product', 'related'));
    }
}
