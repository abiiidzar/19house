<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\InventoryStock;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Sizes
        $sizesData = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $sizes = [];
        foreach ($sizesData as $index => $name) {
            $sizes[$name] = Size::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'display_order' => $index, 'is_active' => true]
            );
        }

        // 2. Buat Categories
        $tees = Category::firstOrCreate(
            ['slug' => 't-shirts'],
            ['name' => 'T-Shirts', 'display_order' => 1, 'is_active' => true]
        );
        $pants = Category::firstOrCreate(
            ['slug' => 'pants'],
            ['name' => 'Pants', 'display_order' => 2, 'is_active' => true]
        );
        $outerwear = Category::firstOrCreate(
            ['slug' => 'outerwear'],
            ['name' => 'Outerwear', 'display_order' => 3, 'is_active' => true]
        );

        // 3. Buat Collections
        $ss24 = Collection::firstOrCreate(
            ['slug' => 'ss24'],
            ['name' => 'SS24 Collection', 'display_order' => 1, 'is_active' => true]
        );
        $essential = Collection::firstOrCreate(
            ['slug' => 'essential'],
            ['name' => 'Essentials', 'display_order' => 2, 'is_active' => true]
        );

        // 4. Definisikan Produk
        $products = [
            [
                'name' => 'Essential Heavyweight Tee',
                'category' => $tees,
                'base_price' => 199000,
                'featured' => true,
                'collections' => [$essential->id, $ss24->id],
                'description' => "Crafted from premium 240gsm cotton. Boxy fit with dropped shoulders.\n\n- 100% Combed Cotton\n- Pre-shrunk\n- Ribbed collar",
                'variants' => [
                    ['name' => 'Black', 'hex' => '#111111', 'image' => 'placeholders/tee-black.jpg'],
                    ['name' => 'White', 'hex' => '#FFFFFF', 'image' => 'placeholders/tee-white.jpg'],
                ]
            ],
            [
                'name' => 'Wide Leg Cargo Pants',
                'category' => $pants,
                'base_price' => 349000,
                'featured' => true,
                'collections' => [$ss24->id],
                'description' => "Loose fit cargo pants with multiple utility pockets.\n\n- 100% Nylon Taslan\n- Water repellent\n- Adjustable drawstring waist",
                'variants' => [
                    ['name' => 'Navy', 'hex' => '#1C2541', 'image' => 'placeholders/cargo-navy.jpg'],
                    ['name' => 'Olive', 'hex' => '#556B2F', 'image' => 'placeholders/cargo-olive.jpg'],
                ]
            ],
            [
                'name' => 'Tech Fleece Hoodie',
                'category' => $outerwear,
                'base_price' => 459000,
                'featured' => false,
                'collections' => [$essential->id],
                'description' => "Oversized hoodie constructed with double-layered tech fleece.\n\n- 100% Polyester Fleece\n- Kangaroo pocket\n- Hidden interior pocket",
                'variants' => [
                    ['name' => 'Grey', 'hex' => '#808080', 'image' => 'placeholders/hoodie-grey.jpg'],
                ]
            ]
        ];

        // 5. Looping dan Insert Produk
        foreach ($products as $pData) {
            $product = Product::firstOrCreate(
                ['slug' => Str::slug($pData['name'])],
                [
                    'name' => $pData['name'],
                    'category_id' => $pData['category']->id,
                    'description' => $pData['description'],
                    'base_price' => $pData['base_price'],
                    'status' => Product::STATUS_ACTIVE,
                    'is_featured' => $pData['featured'],
                ]
            );

            // Sync Collections
            $product->collections()->sync($pData['collections']);

            // Jika produk sudah ada (sudah seeder sebelumnya), skip pembuatan variant agar tidak dobel
            if ($product->variants()->exists()) {
                continue;
            }

            foreach ($pData['variants'] as $vIndex => $vData) {
                $variant = $product->variants()->create([
                    'name' => $vData['name'],
                    'hex_code' => $vData['hex'],
                    'display_order' => $vIndex,
                    'is_active' => true,
                ]);

                // Buat Gambar Variant
                $variant->images()->create([
                    'path' => $vData['image'],
                    'is_primary' => true,
                    'display_order' => 0,
                ]);

                // Buat SKU untuk setiap Size
                foreach ($sizes as $sizeName => $sizeModel) {
                    $skuCode = '19H-' . Str::upper(Str::substr($product->slug, 0, 4)) . '-' . Str::upper(Str::substr($vData['name'], 0, 3)) . '-' . Str::upper($sizeName);

                    $sku = $variant->skus()->create([
                        'product_id' => $product->id,
                        'size_id' => $sizeModel->id,
                        'sku' => $skuCode,
                        'price' => null, // Pakai base_price
                        'is_active' => true,
                    ]);

                    // Buat Stok Inventory
                    InventoryStock::create([
                        'product_sku_id' => $sku->id,
                        'on_hand' => rand(5, 20), // Stok random 5-20
                        'reserved' => 0,
                    ]);
                }
            }
        }
    }
}
