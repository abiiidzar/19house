<?php

namespace App\Services\Catalog;

use App\Models\ProductSku;

class PriceResolver
{
    /**
     * Resolve the final price for a given SKU.
     * If SKU has a specific price, use it. Otherwise, fall back to Product base price.
     */
    public function resolve(ProductSku $sku): int
    {
        if (!is_null($sku->price)) {
            return $sku->price;
        }

        return $sku->product->base_price;
    }
}
