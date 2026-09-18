<?php

namespace Tests\Feature\Admin\Catalog;

use App\Livewire\Admin\Catalog\ProductVariantManager;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Role;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductVariantManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Product $product;

    private Size $sizeS;

    private Size $sizeM;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Admin User
        $this->admin = User::factory()->create([
            'role_id' => Role::factory()->create(['slug' => 'admin'])->id,
            'status' => 'ACTIVE',
        ]);

        // Setup Data Dasar
        $category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'base_price' => 150000,
            'status' => 'DRAFT',
        ]);

        $this->sizeS = Size::factory()->create(['name' => 'S', 'slug' => 's']);
        $this->sizeM = Size::factory()->create(['name' => 'M', 'slug' => 'm']);
    }

    public function test_admin_can_create_variant(): void
    {
        Livewire::actingAs($this->admin)
            ->test(ProductVariantManager::class, ['product' => $this->product])
            ->set('variantName', 'Black')
            ->set('variantHex', '#000000')
            ->call('createVariant')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('product_variants', [
            'product_id' => $this->product->id,
            'name' => 'Black',
            'hex_code' => '#000000',
        ]);
    }

    public function test_admin_can_upload_image_to_variant(): void
    {
        Storage::fake('public');

        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        $file = UploadedFile::fake()->image('variant.jpg');

        Livewire::actingAs($this->admin)
            ->test(ProductVariantManager::class, ['product' => $this->product])
            ->set('newImage', $file)
            ->call('saveImage', $variant->id)
            ->assertHasNoErrors();

        $variant->refresh();

        $this->assertCount(1, $variant->images);
        $this->assertTrue($variant->images->first()->is_primary); // Gambar pertama harus primary
        Storage::disk('public')->assertExists('variants/'.$file->hashName());
    }

    public function test_admin_can_set_primary_image_for_variant(): void
    {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        $first = ProductVariantImage::create(['product_variant_id' => $variant->id, 'path' => 'variants/first.jpg', 'is_primary' => true, 'display_order' => 0]);
        $second = ProductVariantImage::create(['product_variant_id' => $variant->id, 'path' => 'variants/second.jpg', 'is_primary' => false, 'display_order' => 1]);

        Livewire::actingAs($this->admin)
            ->test(ProductVariantManager::class, ['product' => $this->product])
            ->call('setPrimaryImage', $second->id)
            ->assertHasNoErrors();

        $this->assertFalse($first->fresh()->is_primary);
        $this->assertTrue($second->fresh()->is_primary);
        $this->assertSame($variant->id, $second->fresh()->variant->id);
    }

    public function test_admin_can_create_sku_for_variant(): void
    {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);

        Livewire::actingAs($this->admin)
            ->test(ProductVariantManager::class, ['product' => $this->product])
            ->set('selectedVariantId', $variant->id)
            ->set('sizeId', $this->sizeS->id)
            ->set('skuCode', '19H-TEE-BLK-S')
            ->set('skuPrice', 160000)
            ->call('createSku')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('product_skus', [
            'product_id' => $this->product->id,
            'product_variant_id' => $variant->id,
            'size_id' => $this->sizeS->id,
            'sku' => '19H-TEE-BLK-S',
            'price' => 160000,
        ]);
    }

    public function test_sku_code_must_be_unique(): void
    {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        ProductSku::factory()->create([
            'sku' => '19H-TEE-BLK-S',
            'product_id' => $this->product->id,
            'product_variant_id' => $variant->id,
            'size_id' => $this->sizeS->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(ProductVariantManager::class, ['product' => $this->product])
            ->set('selectedVariantId', $variant->id)
            ->set('sizeId', $this->sizeM->id) // Size beda, tapi SKU sama
            ->set('skuCode', '19H-TEE-BLK-S')
            ->call('createSku')
            ->assertHasErrors(['skuCode' => 'unique']);
    }

    public function test_variant_and_size_combination_must_be_unique(): void
    {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
        ProductSku::factory()->create([
            'sku' => '19H-TEE-BLK-S',
            'product_id' => $this->product->id,
            'product_variant_id' => $variant->id,
            'size_id' => $this->sizeS->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(ProductVariantManager::class, ['product' => $this->product])
            ->set('selectedVariantId', $variant->id)
            ->set('sizeId', $this->sizeS->id) // Size sama, SKU beda
            ->set('skuCode', '19H-TEE-BLK-S2')
            ->call('createSku')
            ->assertHasErrors(['sizeId']); // Validasi custom kita menolak ini
    }

    public function test_product_cannot_be_activated_if_not_ready(): void
    {
        $this->actingAs($this->admin)
            ->put(route('admin.products.update', $this->product), [
                'name' => 'Test Product',
                'slug' => 'test-product',
                'base_price' => 150000,
                'status' => 'ACTIVE', // Mencoba mengaktifkan tanpa variant/sku
                'is_featured' => false,
            ])
            ->assertSessionHasErrors('status'); // Ditangkap oleh UpdateProductRequest
    }
}
