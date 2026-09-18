<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->unique()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'base_price' => fake()->numberBetween(100_000, 1_000_000),
            'status' => Product::STATUS_DRAFT,
            'is_featured' => false,
        ];
    }
}
