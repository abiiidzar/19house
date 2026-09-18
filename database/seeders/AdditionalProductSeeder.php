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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class AdditionalProductSeeder extends Seeder
{
    /** @var array<string, array{name: string, hex: string}> */
    private array $colours = [
        'black' => ['name' => 'Black', 'hex' => '#111111'],
        'bone' => ['name' => 'Bone', 'hex' => '#E7E1D5'],
        'navy' => ['name' => 'Navy', 'hex' => '#18243A'],
        'olive' => ['name' => 'Olive', 'hex' => '#596148'],
        'charcoal' => ['name' => 'Charcoal', 'hex' => '#414141'],
        'stone' => ['name' => 'Stone', 'hex' => '#B8B0A3'],
        'brown' => ['name' => 'Earth Brown', 'hex' => '#694C3A'],
        'blue' => ['name' => 'Washed Blue', 'hex' => '#71869B'],
    ];

    public function run(): void
    {
        $sizes = $this->sizes();
        $categories = $this->categories();
        $collections = $this->collections();

        foreach ($this->products() as $index => $data) {
            $product = Product::withTrashed()->updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'category_id' => $categories[$data['category']]->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'base_price' => $data['price'],
                    'status' => Product::STATUS_ACTIVE,
                    'is_featured' => $data['featured'],
                    'deleted_at' => null,
                ],
            );

            $product->collections()->syncWithPivotValues(
                collect($data['collections'])->map(fn (string $slug) => $collections[$slug]->id)->all(),
                ['display_order' => $index],
            );

            $colour = $this->colours[$data['colour']];
            $variant = ProductVariant::withTrashed()->updateOrCreate(
                ['product_id' => $product->id, 'name' => $colour['name']],
                ['hex_code' => $colour['hex'], 'display_order' => 0, 'is_active' => true, 'deleted_at' => null],
            );

            ProductVariantImage::updateOrCreate(
                ['product_variant_id' => $variant->id, 'display_order' => 0],
                ['path' => $this->downloadImage($product->slug, $data['image']), 'is_primary' => true],
            );

            foreach ($sizes as $size) {
                $sku = ProductSku::withTrashed()->updateOrCreate(
                    ['sku' => $this->sku($index, $data['category'], $data['colour'], $size->name)],
                    [
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'size_id' => $size->id,
                        'price' => null,
                        'is_active' => true,
                        'deleted_at' => null,
                    ],
                );

                InventoryStock::updateOrCreate(
                    ['product_sku_id' => $sku->id],
                    ['on_hand' => 8 + (($index + $size->display_order) % 17), 'reserved' => 0],
                );
            }
        }
    }

    /** @return array<string, Size> */
    private function sizes(): array
    {
        return collect(['S', 'M', 'L', 'XL'])->mapWithKeys(function (string $name, int $index): array {
            $size = Size::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'display_order' => $index + 2, 'is_active' => true],
            );

            return [$name => $size];
        })->all();
    }

    /** @return array<string, Category> */
    private function categories(): array
    {
        $data = [
            't-shirts' => ['T-Shirts', 'Everyday tees and graphic layers.'],
            'shirts' => ['Shirts', 'Relaxed shirts for daily rotation.'],
            'pants' => ['Pants', 'Utility, tailored, and relaxed trousers.'],
            'outerwear' => ['Outerwear', 'Transitional layers built beyond seasons.'],
            'accessories' => ['Accessories', 'Functional finishing pieces.'],
        ];

        return collect($data)->mapWithKeys(function (array $item, string $slug) use ($data): array {
            $category = Category::withTrashed()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $item[0], 'description' => $item[1], 'display_order' => array_search($slug, array_keys($data), true) + 1, 'is_active' => true, 'deleted_at' => null],
            );

            return [$slug => $category];
        })->all();
    }

    /** @return array<string, Collection> */
    private function collections(): array
    {
        $data = [
            'essential' => ['Essentials', 'Quiet wardrobe foundations for everyday life.'],
            'new-form' => ['New Form', 'Modern proportions and considered utility.'],
            'after-hours' => ['After Hours', 'Darker tones and refined evening layers.'],
        ];

        return collect($data)->mapWithKeys(function (array $item, string $slug) use ($data): array {
            $collection = Collection::withTrashed()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $item[0], 'description' => $item[1], 'display_order' => array_search($slug, array_keys($data), true) + 1, 'is_active' => true, 'deleted_at' => null],
            );

            return [$slug => $collection];
        })->all();
    }

    private function sku(int $index, string $category, string $colour, string $size): string
    {
        return sprintf('19H-%02d-%s-%s-%s', $index + 1, Str::upper(Str::substr($category, 0, 3)), Str::upper(Str::substr($colour, 0, 3)), $size);
    }

    private function downloadImage(string $slug, string $url): string
    {
        $path = "products/seeded/{$slug}.jpg";

        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        try {
            $response = Http::timeout(20)->retry(2, 300)->get($url);

            if ($response->successful() && str_starts_with($response->header('Content-Type'), 'image/')) {
                Storage::disk('public')->put($path, $response->body());

                return $path;
            }
        } catch (Throwable) {
            // Seeding must remain usable without an internet connection.
        }

        return 'mentah/19house.png';
    }

    /** @return list<array{name: string, category: string, price: int, colour: string, featured: bool, collections: list<string>, description: string, image: string}> */
    private function products(): array
    {
        $images = [
            'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1523398002811-999ca8dec234?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1583743814966-8936f37f4678?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1608234807905-4466023792f5?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1578681994506-b8f463449011?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=1200&q=85',
        ];

        $products = [
            ['Studio Weight Tee', 't-shirts', 229000, 'black', true, ['essential', 'new-form']],
            ['Boxy Pocket Tee', 't-shirts', 219000, 'bone', false, ['essential']],
            ['Mercerized Daily Tee', 't-shirts', 279000, 'navy', false, ['essential', 'after-hours']],
            ['Raw Edge Long Sleeve', 't-shirts', 299000, 'charcoal', true, ['new-form', 'after-hours']],
            ['Relaxed Oxford Shirt', 'shirts', 389000, 'blue', true, ['essential', 'new-form']],
            ['Washed Poplin Shirt', 'shirts', 369000, 'bone', false, ['essential']],
            ['Utility Overshirt', 'shirts', 449000, 'olive', true, ['new-form']],
            ['Evening Collar Shirt', 'shirts', 419000, 'black', false, ['after-hours']],
            ['Straight Work Trousers', 'pants', 429000, 'charcoal', true, ['essential', 'new-form']],
            ['Pleated Wide Trousers', 'pants', 469000, 'black', true, ['new-form', 'after-hours']],
            ['Washed Carpenter Pants', 'pants', 449000, 'brown', false, ['new-form']],
            ['Relaxed Drawcord Pants', 'pants', 399000, 'stone', false, ['essential']],
            ['Cropped Field Jacket', 'outerwear', 649000, 'olive', true, ['new-form']],
            ['Minimal Coach Jacket', 'outerwear', 589000, 'navy', false, ['essential']],
            ['Structured Work Jacket', 'outerwear', 679000, 'brown', true, ['new-form', 'after-hours']],
            ['Lightweight City Parka', 'outerwear', 729000, 'black', false, ['after-hours']],
            ['Canvas Daily Tote', 'accessories', 189000, 'bone', false, ['essential']],
            ['Panel Cap', 'accessories', 179000, 'charcoal', false, ['essential', 'new-form']],
            ['Utility Crossbody Bag', 'accessories', 329000, 'black', true, ['new-form', 'after-hours']],
            ['Ribbed Everyday Beanie', 'accessories', 159000, 'olive', false, ['essential']],
        ];

        return collect($products)->map(fn (array $item, int $index): array => [
            'name' => $item[0],
            'category' => $item[1],
            'price' => $item[2],
            'colour' => $item[3],
            'featured' => $item[4],
            'collections' => $item[5],
            'description' => "A considered 19HOUSE silhouette designed for everyday rotation. Built with a relaxed proportion, clean finishing, and durable fabric selected for repeat wear.\n\n- Relaxed contemporary fit\n- Carefully finished construction\n- Designed in Indonesia",
            'image' => $images[$index],
        ])->all();
    }
}
