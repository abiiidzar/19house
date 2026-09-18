<?php

namespace App\View\Components\Storefront;

use App\Models\Product;
use App\Services\Catalog\PriceResolver;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public mixed $firstImage;

    public int $price;

    public bool $soldOut;

    public function __construct(public Product $product, PriceResolver $priceResolver)
    {
        $firstVariant = $product->variants->firstWhere('is_active', true);
        $this->firstImage = $firstVariant?->images->firstWhere('is_primary', true)
            ?? $firstVariant?->images->first();
        $this->price = $firstVariant?->skus->isNotEmpty()
            ? $priceResolver->resolve($firstVariant->skus->first())
            : $product->base_price;
        $this->soldOut = $product->skus->isEmpty()
            || $product->skus->every(fn ($sku) => ($sku->stock?->available ?? 0) <= 0);
    }

    public function render(): View
    {
        return view('components.storefront.product-card');
    }
}
