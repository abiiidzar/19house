<?php

namespace Tests\Feature\Cashier;

use App\Livewire\Cashier\PosScreen;
use App\Models\InventoryStock;
use App\Models\Permission;
use App\Models\PosTransaction;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Size;
use App\Models\User;
use App\Services\Pos\PosService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class PosTest extends TestCase
{
    use RefreshDatabase;

    private User $cashier;

    private ProductSku $sku;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::factory()->create(['slug' => 'cashier']);
        $this->cashier = User::factory()->create(['role_id' => $role->id, 'status' => 'ACTIVE']);
        $product = Product::factory()->create(['name' => 'Classic Tee', 'status' => Product::STATUS_ACTIVE, 'base_price' => 100000]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'name' => 'Black', 'is_active' => true]);
        $size = Size::factory()->create(['name' => 'M']);
        $this->sku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'size_id' => $size->id,
            'sku' => 'TEE-BLK-M',
            'price' => 100000,
            'is_active' => true,
        ]);
        InventoryStock::create(['product_sku_id' => $this->sku->id, 'on_hand' => 5, 'reserved' => 2]);
    }

    private function cart(int $quantity = 1): array
    {
        return [['sku_id' => $this->sku->id, 'quantity' => $quantity]];
    }

    public function test_cash_sale_is_atomic_and_does_not_take_reserved_online_stock(): void
    {
        $transaction = app(PosService::class)->processCheckout($this->cashier, $this->cart(3), 'CASH', 350000);

        $this->assertSame(300000, $transaction->total);
        $this->assertSame(50000, $transaction->change);
        $this->assertSame(2, $this->sku->fresh()->stock->on_hand);
        $this->assertSame(2, $this->sku->fresh()->stock->reserved);
        $this->assertDatabaseHas('inventory_movements', [
            'product_sku_id' => $this->sku->id,
            'type' => 'POS_SALE',
            'quantity' => 3,
            'reference' => $transaction->receipt_number,
        ]);
        $this->assertSame('Classic Tee', $transaction->items->first()->sku_snapshot['product_name']);

        $this->actingAs($this->cashier)->get(route('cashier.receipt.show', $transaction))->assertOk()->assertSee('Print Receipt')->assertSee('TEE-BLK-M');
        $this->get(route('cashier.transactions.index'))->assertOk()->assertSee($transaction->receipt_number);
    }

    public function test_insufficient_available_stock_rolls_back_whole_sale(): void
    {
        try {
            app(PosService::class)->processCheckout($this->cashier, $this->cart(4), 'CASH', 400000);
            $this->fail('Expected an insufficient stock error.');
        } catch (\Exception $exception) {
            $this->assertStringContainsString('Insufficient available stock', $exception->getMessage());
        }

        $this->assertDatabaseCount('pos_transactions', 0);
        $this->assertDatabaseCount('pos_transaction_items', 0);
        $this->assertDatabaseCount('inventory_movements', 0);
        $this->assertSame(5, $this->sku->fresh()->stock->on_hand);
    }

    public function test_failure_on_later_sku_rolls_back_earlier_sku_deduction(): void
    {
        $secondSku = ProductSku::factory()->create([
            'product_id' => $this->sku->product_id,
            'product_variant_id' => $this->sku->product_variant_id,
            'size_id' => Size::factory()->create(['name' => 'L'])->id,
            'is_active' => true,
        ]);
        InventoryStock::create(['product_sku_id' => $secondSku->id, 'on_hand' => 1, 'reserved' => 1]);

        try {
            app(PosService::class)->processCheckout($this->cashier, [
                ['sku_id' => $this->sku->id, 'quantity' => 1],
                ['sku_id' => $secondSku->id, 'quantity' => 1],
            ], 'CASH', 300000);
            $this->fail('Expected later SKU to be unavailable.');
        } catch (\Exception $exception) {
            $this->assertStringContainsString('Insufficient available stock', $exception->getMessage());
        }

        $this->assertDatabaseCount('pos_transactions', 0);
        $this->assertDatabaseCount('pos_transaction_items', 0);
        $this->assertDatabaseCount('inventory_movements', 0);
        $this->assertSame(5, $this->sku->fresh()->stock->on_hand);
    }

    public function test_cash_received_below_total_is_rejected_without_inventory_change(): void
    {
        $this->expectException(ValidationException::class);
        try {
            app(PosService::class)->processCheckout($this->cashier, $this->cart(), 'CASH', 99999);
        } finally {
            $this->assertDatabaseCount('pos_transactions', 0);
            $this->assertSame(5, $this->sku->fresh()->stock->on_hand);
        }
    }

    public function test_non_cash_requires_manual_confirmation_and_reference(): void
    {
        $this->expectException(ValidationException::class);
        try {
            app(PosService::class)->processCheckout($this->cashier, $this->cart(), 'QRIS', 0, 0, 'QR-123', false);
        } finally {
            $this->assertDatabaseCount('pos_transactions', 0);
            $this->assertSame(5, $this->sku->fresh()->stock->on_hand);
        }
    }

    public function test_verified_non_cash_sale_records_reference_and_deducts_stock(): void
    {
        $transaction = app(PosService::class)->processCheckout($this->cashier, $this->cart(), 'QRIS', 0, 0, 'QR-123', true);

        $this->assertSame('QR-123', $transaction->payment_reference);
        $this->assertSame(PosTransaction::PAYMENT_CONFIRMED, $transaction->payment_status);
        $this->assertNull($transaction->amount_received);
        $this->assertSame(4, $this->sku->fresh()->stock->on_hand);
    }

    public function test_discount_requires_permission_and_is_capped(): void
    {
        try {
            app(PosService::class)->processCheckout($this->cashier, $this->cart(), 'CASH', 100000, 10000);
            $this->fail('Expected unauthorized discount.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('discount', $exception->errors());
        }

        $permission = Permission::create(['name' => 'Apply POS Discount', 'slug' => 'pos.discount.apply']);
        $this->cashier->role->permissions()->attach($permission);
        try {
            app(PosService::class)->processCheckout($this->cashier, $this->cart(), 'CASH', 100000, 10001);
            $this->fail('Expected discount above cap to fail.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('discount', $exception->errors());
        }

        $transaction = app(PosService::class)->processCheckout($this->cashier, $this->cart(), 'CASH', 90000, 10000);
        $this->assertSame(90000, $transaction->total);
        $this->assertSame(10000, $transaction->discount);
    }

    public function test_search_by_name_or_sku_shows_available_stock_and_hides_inactive_skus(): void
    {
        Livewire::actingAs($this->cashier)->test(PosScreen::class)
            ->assertSee('Classic Tee')
            ->assertSee('1 products')
            ->set('search', 'Classic')
            ->assertSee('Classic Tee')
            ->assertSee('3 units available')
            ->set('search', 'TEE-BLK-M')
            ->assertSee('Classic Tee');

        $this->sku->update(['is_active' => false]);
        Livewire::actingAs($this->cashier)->test(PosScreen::class)
            ->set('search', 'TEE-BLK-M')
            ->assertDontSee('Classic Tee');
    }

    public function test_cashier_selects_color_and_size_before_adding_a_product(): void
    {
        $blue = ProductVariant::factory()->create(['product_id' => $this->sku->product_id, 'name' => 'Blue', 'is_active' => true]);
        $blueSku = ProductSku::factory()->create([
            'product_id' => $this->sku->product_id,
            'product_variant_id' => $blue->id,
            'size_id' => $this->sku->size_id,
            'sku' => 'TEE-BLU-M',
            'is_active' => true,
        ]);
        InventoryStock::create(['product_sku_id' => $blueSku->id, 'on_hand' => 2, 'reserved' => 0]);

        Livewire::actingAs($this->cashier)->test(PosScreen::class)
            ->assertSee('Classic Tee')
            ->assertSee('Black')
            ->assertSee('Blue')
            ->call('selectVariant', $this->sku->product_id, $blue->id)
            ->assertSee('TEE-BLU-M', escape: false)
            ->call('selectSize', $this->sku->product_id, $blueSku->id)
            ->call('addProductToCart', $this->sku->product_id)
            ->assertSet('cart.'.$blueSku->id.'.quantity', 1)
            ->assertHasNoErrors();
    }

    public function test_cashier_can_complete_sale_through_livewire_screen(): void
    {
        Livewire::actingAs($this->cashier)->test(PosScreen::class)
            ->call('addToCart', $this->sku->id)
            ->set('amountReceived', '100000')
            ->call('checkout')
            ->assertHasNoErrors();

        $transaction = PosTransaction::firstOrFail();
        $this->assertSame(100000, $transaction->total);
        $this->assertSame(4, $this->sku->fresh()->stock->on_hand);
    }

    public function test_only_cashier_can_access_pos_routes(): void
    {
        $customerRole = Role::factory()->create(['slug' => 'customer']);
        $customer = User::factory()->create(['role_id' => $customerRole->id, 'status' => 'ACTIVE']);
        $this->actingAs($customer)->get(route('cashier.pos.index'))->assertForbidden();
        $this->actingAs($cashier = $this->cashier)->get(route('cashier.pos.index'))->assertOk();
    }
}
