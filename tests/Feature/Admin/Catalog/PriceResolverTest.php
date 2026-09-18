<?php

namespace Tests\Feature\Admin\Catalog;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSku;
use App\Services\Catalog\PriceResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceResolverTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'base_price' => 200000,
        ]);
    }

    public function test_it_returns_product_base_price_if_sku_price_is_null(): void
    {
        $sku = ProductSku::factory()->create([
            'product_id' => $this->product->id,
            'price' => null,
        ]);

        $resolver = new PriceResolver;
        $resolvedPrice = $resolver->resolve($sku);

        $this->assertEquals(200000, $resolvedPrice);
    }

    public function test_it_returns_sku_price_if_it_is_set(): void
    {
        $sku = ProductSku::factory()->create([
            'product_id' => $this->product->id,
            'price' => 250000,
        ]);

        $resolver = new PriceResolver;
        $resolvedPrice = $resolver->resolve($sku);

        $this->assertEquals(250000, $resolvedPrice);
    }
}
