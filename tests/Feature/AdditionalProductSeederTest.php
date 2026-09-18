<?php

namespace Tests\Feature;

use Database\Seeders\AdditionalProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdditionalProductSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_twenty_complete_products_idempotently(): void
    {
        Storage::fake('public');
        Http::fake([
            '*' => Http::response('fake-jpeg-content', 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $this->seed(AdditionalProductSeeder::class);
        $this->seed(AdditionalProductSeeder::class);

        $this->assertDatabaseCount('products', 20);
        $this->assertDatabaseCount('product_variants', 20);
        $this->assertDatabaseCount('product_variant_images', 20);
        $this->assertDatabaseCount('product_skus', 80);
        $this->assertDatabaseCount('inventory_stocks', 80);

        $this->assertDatabaseHas('categories', ['slug' => 'accessories', 'is_active' => true]);
        $this->assertDatabaseHas('collections', ['slug' => 'new-form', 'is_active' => true]);
        $this->assertDatabaseHas('products', ['slug' => 'studio-weight-tee', 'status' => 'ACTIVE']);
        $this->assertDatabaseHas('product_variant_images', ['path' => 'products/seeded/studio-weight-tee.jpg', 'is_primary' => true]);

        Storage::disk('public')->assertExists('products/seeded/studio-weight-tee.jpg');
    }
}
