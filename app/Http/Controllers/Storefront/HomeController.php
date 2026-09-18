<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Services\Catalog\PriceResolver;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(PriceResolver $priceResolver): View
    {
        $relations = ['category', 'variants.images', 'variants.skus.stock', 'skus.stock'];
        $newArrivals = Product::with($relations)->where('status', Product::STATUS_ACTIVE)->latest()->take(4)->get();
        $featured = Product::with($relations)->where('is_featured', true)->where('status', Product::STATUS_ACTIVE)->take(4)->get();
        $featuredCollection = Collection::query()
            ->where('is_active', true)
            ->with(['products' => fn ($query) => $query
                ->where('status', Product::STATUS_ACTIVE)
                ->with($relations)])
            ->orderBy('display_order')
            ->first();

        $hero = HomepageSection::where('slot', 'hero')->where('is_active', true)->first();
        $editorials = HomepageSection::where('type', 'editorial')->where('is_active', true)
            ->whereNotNull('image_path')->orderBy('sort_order')->orderBy('id')->get();

        $detailProduct = Product::query()
            ->with($relations)
            ->where('status', Product::STATUS_ACTIVE)
            ->whereHas('variants.images')
            ->orderByDesc('is_featured')
            ->latest()
            ->first();

        $detailVariant = $detailProduct?->variants->first(
            fn ($variant) => $variant->is_active && $variant->images->isNotEmpty()
        );
        $detailImage = $detailVariant?->images->firstWhere('is_primary', true)
            ?? $detailVariant?->images->first();
        $detailSku = $detailVariant?->skus->first();
        $detailPrice = $detailSku ? $priceResolver->resolve($detailSku) : $detailProduct?->base_price;

        return view('storefront.home', compact(
            'newArrivals',
            'featured',
            'featuredCollection',
            'hero',
            'editorials',
            'detailProduct',
            'detailImage',
            'detailPrice',
        ));
    }
}
