<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductSku>
 */
class ProductSkuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'size_id' => Size::factory(),
            'sku' => fake()->unique()->bothify('19H-####-???'),
            'price' => null,
        ];
    }
}
