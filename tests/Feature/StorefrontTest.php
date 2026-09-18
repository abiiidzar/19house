<?php

namespace Tests\Feature;

use App\Livewire\Storefront\ProductDetail;
use App\Livewire\Storefront\WishlistButton;
use App\Models\Category;
use App\Models\InventoryStock;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Role;
use App\Models\Size;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shop_and_search_render_without_catalog_data(): void
    {
        $this->get(route('home'))->assertOk();
        $this->get(route('shop.index'))->assertOk();
        $this->get(route('search', ['search' => 'tee']))->assertOk();
    }

    public function test_header_shows_profile_dropdown_for_signed_in_user(): void
    {
        $user = User::factory()->create(['name' => 'Aulia']);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Buka menu akun Aulia')
            ->assertSee('Profile')
            ->assertSee('Logout')
            ->assertSee(route('profile.edit'));
    }

    public function test_header_shows_correct_dashboard_link_only_for_staff_roles(): void
    {
        foreach (['admin', 'cashier', 'management'] as $roleSlug) {
            $role = Role::factory()->create(['slug' => $roleSlug]);
            $user = User::factory()->create(['role_id' => $role->id]);

            $this->actingAs($user)
                ->get(route('home'))
                ->assertOk()
                ->assertSee('Dashboard')
                ->assertSee($user->dashboardRoute());
        }

        $customerRole = Role::factory()->create(['slug' => 'customer']);
        $customer = User::factory()->create(['role_id' => $customerRole->id]);
        $this->actingAs($customer)->get(route('home'))->assertOk()->assertDontSee('Dashboard');
    }

    public function test_active_product_is_public_and_inactive_product_is_hidden(): void
    {
        [$active] = $this->sellableProduct('Essential Tee', Product::STATUS_ACTIVE);
        $inactive = Product::factory()->create(['name' => 'Hidden Tee', 'slug' => 'hidden-tee', 'status' => Product::STATUS_INACTIVE]);

        $this->get(route('products.show', $active))->assertOk()->assertSee('Essential Tee');
        $this->get(route('products.show', $inactive))->assertNotFound();
    }

    public function test_search_and_category_only_show_matching_active_products(): void
    {
        [$product, , , $category] = $this->sellableProduct('Essential Tee', Product::STATUS_ACTIVE);
        Product::factory()->create(['name' => 'Hidden Tee', 'slug' => 'hidden-tee', 'status' => Product::STATUS_INACTIVE, 'category_id' => $category->id]);

        $this->get(route('search', ['search' => 'Essential']))
            ->assertOk()->assertSee($product->name)->assertDontSee('Hidden Tee');
        $this->get(route('categories.show', $category->slug))
            ->assertOk()->assertSee($product->name)->assertDontSee('Hidden Tee');
    }

    public function test_product_detail_switches_variant_and_size_using_inventory_state(): void
    {
        [$product, $variant, $sku] = $this->sellableProduct('Essential Tee', Product::STATUS_ACTIVE);

        Livewire::test(ProductDetail::class, ['product' => $product->load('variants.images', 'variants.skus.size', 'variants.skus.stock')])
            ->call('selectVariant', $variant->id)
            ->call('selectSize', $sku->size_id)
            ->assertSet('selectedVariantId', $variant->id)
            ->assertSet('selectedSkuId', $sku->id)
            ->assertSet('stockAvailable', 5)
            ->assertSee('In stock');
    }

    public function test_guest_is_prompted_to_log_in_for_wishlist_and_cart(): void
    {
        [$product, $variant, $sku] = $this->sellableProduct('Essential Tee', Product::STATUS_ACTIVE);

        Livewire::test(WishlistButton::class, ['product' => $product])
            ->call('toggle')
            ->assertDispatched('auth-required', action: 'wishlist');

        Livewire::test(ProductDetail::class, ['product' => $product->load('variants.images', 'variants.skus.size', 'variants.skus.stock')])
            ->call('selectVariant', $variant->id)
            ->call('selectSize', $sku->size_id)
            ->call('addToCart')
            ->assertDispatched('auth-required', action: 'cart');

        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseCount('wishlists', 0);
    }

    public function test_unavailable_variant_uses_small_notification_instead_of_browser_alert(): void
    {
        [$product, $variant, $sku] = $this->sellableProduct('Essential Tee', Product::STATUS_ACTIVE);
        $role = Role::factory()->create(['slug' => 'customer']);
        $customer = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($customer);

        $variant->update(['is_active' => false]);

        Livewire::test(ProductDetail::class, ['product' => $product->load('variants.images', 'variants.skus.size', 'variants.skus.stock')])
            ->set('selectedSkuId', $sku->id)
            ->call('addToCart')
            ->assertDispatched('storefront-notice', message: 'Product or Variant is not available.');
    }

    public function test_customer_can_add_active_product_variant_to_cart(): void
    {
        [$product, $variant, $sku] = $this->sellableProduct('Essential Tee', Product::STATUS_ACTIVE);
        $role = Role::factory()->create(['slug' => 'customer']);
        $this->actingAs(User::factory()->create(['role_id' => $role->id]));

        $this->assertSame($variant->id, $sku->fresh()->variant?->id);

        Livewire::test(ProductDetail::class, ['product' => $product->load('variants.images', 'variants.skus.size', 'variants.skus.stock')])
            ->call('selectVariant', $variant->id)
            ->call('selectSize', $sku->size_id)
            ->call('addToCart')
            ->assertDispatched('cart-updated');

        $this->assertDatabaseHas('cart_items', [
            'product_sku_id' => $sku->id,
            'quantity' => 1,
        ]);
    }

    public function test_cart_shows_checkout_button_only_when_all_items_are_available(): void
    {
        [, , $sku] = $this->sellableProduct('Essential Tee', Product::STATUS_ACTIVE);
        $role = Role::factory()->create(['slug' => 'customer']);
        $this->actingAs(User::factory()->create(['role_id' => $role->id]));
        app(CartService::class)->add($sku->id, 1);

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee(route('checkout.index'))
            ->assertSee('Checkout');

        $sku->stock->update(['on_hand' => 0]);

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Perbarui jumlah atau hapus produk yang tidak tersedia sebelum checkout.');
    }

    private function sellableProduct(string $name, string $status): array
    {
        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'status' => $status,
        ]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'is_active' => true]);
        ProductVariantImage::create(['product_variant_id' => $variant->id, 'path' => 'variants/test.jpg', 'is_primary' => true, 'display_order' => 0]);
        $size = Size::factory()->create();
        $sku = ProductSku::factory()->create(['product_id' => $product->id, 'product_variant_id' => $variant->id, 'size_id' => $size->id]);
        InventoryStock::create(['product_sku_id' => $sku->id, 'on_hand' => 5, 'reserved' => 0]);

        return [$product, $variant, $sku, $category];
    }
}
