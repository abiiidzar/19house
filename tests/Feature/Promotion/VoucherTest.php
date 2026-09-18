<?php

namespace Tests\Feature\Promotion;

use App\Livewire\Checkout\CheckoutForm;
use App\Models\CustomerAddress;
use App\Models\InventoryStock;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Size;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Services\Cart\CartService;
use App\Services\Checkout\CheckoutService;
use App\Services\Promotion\VoucherService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    private CustomerAddress $address;

    private ProductSku $sku;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::factory()->create(['slug' => 'customer']);
        $this->customer = User::factory()->create(['role_id' => $role->id, 'status' => 'ACTIVE']);
        $this->address = CustomerAddress::create([
            'user_id' => $this->customer->id,
            'recipient_name' => 'Customer',
            'phone' => '081234567890',
            'address_line_1' => 'Test Street 1',
            'city' => 'Jakarta',
            'postal_code' => '12345',
            'is_default' => true,
        ]);
        $product = Product::factory()->create(['status' => Product::STATUS_ACTIVE, 'base_price' => 100000]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'is_active' => true]);
        $this->sku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'size_id' => Size::factory()->create()->id,
            'price' => 100000,
            'is_active' => true,
        ]);
        InventoryStock::create(['product_sku_id' => $this->sku->id, 'on_hand' => 20, 'reserved' => 0]);
        $this->actingAs($this->customer);
    }

    private function voucher(array $attributes = []): Voucher
    {
        return Voucher::create(array_merge([
            'code' => 'SAVE10',
            'type' => Voucher::TYPE_PERCENTAGE,
            'value' => 10,
            'min_purchase' => 0,
            'usage_limit' => 10,
            'per_user_limit' => 1,
            'is_active' => true,
        ], $attributes));
    }

    private function addCartItem(): void
    {
        app(CartService::class)->add($this->sku->id, 1);
    }

    private function checkout(?string $code): Order
    {
        return app(CheckoutService::class)->processCheckout($this->address->id, 'REGULAR', 'BANK_TRANSFER', $code);
    }

    public function test_fixed_discount_is_capped_at_subtotal_and_usage_is_redeemed_once_on_payment(): void
    {
        $this->voucher(['type' => Voucher::TYPE_FIXED, 'value' => 150000, 'code' => 'FIXED']);
        $this->addCartItem();

        $order = $this->checkout(' fixed ');

        $this->assertSame(100000, $order->discount);
        $this->assertSame(20000, $order->total);
        $this->assertSame(20000, $order->payment->amount);
        $this->assertSame(VoucherUsage::STATUS_RESERVED, $order->voucherUsage->status);
        $this->postJson(route('webhooks.payments'), ['payment_reference' => $order->payment->payment_reference])->assertOk();
        $this->postJson(route('webhooks.payments'), ['payment_reference' => $order->payment->payment_reference])->assertOk();

        $this->assertSame(VoucherUsage::STATUS_REDEEMED, $order->fresh()->voucherUsage->status);
        $this->assertDatabaseCount('voucher_usages', 1);
    }

    public function test_percentage_discount_obeys_maximum_and_checkout_ignores_client_preview_amount(): void
    {
        $this->voucher(['value' => 50, 'max_discount' => 30000]);
        $this->addCartItem();

        Livewire::actingAs($this->customer)->test(CheckoutForm::class)
            ->set('voucherCode', 'SAVE10')
            ->call('applyVoucher')
            ->assertSet('discount', 30000)
            ->set('discount', 99000)
            ->call('placeOrder')
            ->assertHasNoErrors();

        $order = Order::firstOrFail();
        $this->assertSame(30000, $order->discount);
        $this->assertSame(90000, $order->total);
        $this->assertSame(30000, $order->voucherUsage->discount_amount);
    }

    public function test_invalid_period_minimum_inactive_and_bad_percentage_are_rejected(): void
    {
        $voucher = $this->voucher(['min_purchase' => 200000]);
        $this->assertVoucherRejected('Minimum purchase');

        $voucher->update(['min_purchase' => 0, 'start_date' => now()->addDay()]);
        $this->assertVoucherRejected('not yet valid');

        $voucher->update(['start_date' => null, 'end_date' => now()->subDay()]);
        $this->assertVoucherRejected('expired');

        $voucher->update(['end_date' => null, 'is_active' => false]);
        $this->assertVoucherRejected('invalid or inactive');

        $voucher->update(['is_active' => true, 'value' => 101]);
        $this->assertVoucherRejected('configuration is invalid');
    }

    public function test_global_quota_is_reserved_at_checkout_and_released_on_unpaid_cancellation(): void
    {
        $this->voucher(['usage_limit' => 1]);
        $this->addCartItem();
        $firstOrder = $this->checkout('SAVE10');

        $secondCustomer = User::factory()->create(['role_id' => $this->customer->role_id, 'status' => 'ACTIVE']);
        $secondAddress = CustomerAddress::create([
            'user_id' => $secondCustomer->id,
            'recipient_name' => 'Second',
            'phone' => '081234567891',
            'address_line_1' => 'Test Street 2',
            'city' => 'Jakarta',
            'postal_code' => '12345',
        ]);
        $this->actingAs($secondCustomer);
        $this->addCartItem();

        try {
            app(CheckoutService::class)->processCheckout($secondAddress->id, 'REGULAR', 'BANK_TRANSFER', 'SAVE10');
            $this->fail('Expected quota error.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('usage limit', $exception->errors()['voucherCode'][0]);
        }
        $this->assertDatabaseCount('orders', 1);

        $this->actingAs($this->customer)->post(route('customer.orders.cancel', $firstOrder), ['reason' => 'Changed my mind'])->assertSessionHasNoErrors();
        $this->assertSame(VoucherUsage::STATUS_RELEASED, $firstOrder->fresh()->voucherUsage->status);

        $this->actingAs($secondCustomer);
        $secondOrder = app(CheckoutService::class)->processCheckout($secondAddress->id, 'REGULAR', 'BANK_TRANSFER', 'SAVE10');
        $this->assertSame(10000, $secondOrder->discount);
        $this->assertDatabaseCount('voucher_usages', 2);
    }

    public function test_per_user_limit_counts_redeemed_usage(): void
    {
        $this->voucher(['usage_limit' => 10, 'per_user_limit' => 1]);
        $this->addCartItem();
        $order = $this->checkout('SAVE10');
        $this->postJson(route('webhooks.payments'), ['payment_reference' => $order->payment->payment_reference])->assertOk();
        $this->addCartItem();

        try {
            $this->checkout('SAVE10');
            $this->fail('Expected per-user limit error.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('usage limit', $exception->errors()['voucherCode'][0]);
        }
    }

    public function test_database_prevents_stacking_two_vouchers_on_one_order(): void
    {
        $this->voucher();
        $secondVoucher = $this->voucher(['code' => 'ANOTHER']);
        $this->addCartItem();
        $order = $this->checkout('SAVE10');

        try {
            VoucherUsage::create([
                'voucher_id' => $secondVoucher->id,
                'order_id' => $order->id,
                'user_id' => $this->customer->id,
                'discount_amount' => 5000,
                'status' => VoucherUsage::STATUS_RESERVED,
            ]);
            $this->fail('Expected the unique order constraint to reject stacking.');
        } catch (QueryException $exception) {
            $this->assertDatabaseCount('voucher_usages', 1);
        }
    }

    public function test_expired_payment_releases_voucher_quota(): void
    {
        $this->voucher(['usage_limit' => 1]);
        $this->addCartItem();
        $order = $this->checkout('SAVE10');
        $order->payment->update(['expires_at' => now()->subMinute()]);

        $this->artisan('payments:expire')->assertSuccessful();

        $this->assertSame(VoucherUsage::STATUS_RELEASED, $order->fresh()->voucherUsage->status);
    }

    public function test_only_admin_can_manage_vouchers_and_review_usage_history(): void
    {
        $this->get(route('admin.vouchers.index'))->assertForbidden();

        $admin = User::factory()->create(['role_id' => Role::factory()->create(['slug' => 'admin'])->id, 'status' => 'ACTIVE']);
        $this->actingAs($admin)->post(route('admin.vouchers.store'), [
            'code' => ' launch-20 ',
            'type' => Voucher::TYPE_PERCENTAGE,
            'value' => 20,
            'min_purchase' => 50000,
            'max_discount' => 25000,
            'usage_limit' => 2,
            'per_user_limit' => 1,
            'is_active' => 1,
        ])->assertSessionHasNoErrors();

        $voucher = Voucher::firstOrFail();
        $this->assertSame('LAUNCH-20', $voucher->code);
        $this->get(route('admin.vouchers.index'))->assertOk()->assertSee('LAUNCH-20');

        $this->post(route('admin.vouchers.store'), [
            'code' => 'INVALID',
            'type' => Voucher::TYPE_PERCENTAGE,
            'value' => 101,
            'min_purchase' => 0,
            'per_user_limit' => 1,
        ])->assertSessionHasErrors('value');

        $this->put(route('admin.vouchers.update', $voucher), [
            'code' => 'LAUNCH-20',
            'type' => Voucher::TYPE_PERCENTAGE,
            'value' => 20,
            'min_purchase' => 50000,
            'max_discount' => 25000,
            'usage_limit' => 2,
            'per_user_limit' => 1,
        ])->assertSessionHasNoErrors();
        $this->assertFalse($voucher->fresh()->is_active);
        $this->get(route('admin.vouchers.show', $voucher))->assertOk()->assertSee('Usage history');

        $this->actingAs($this->customer);
        $voucher->fresh()->update(['is_active' => true]);
        $this->addCartItem();
        $this->checkout('LAUNCH-20');

        $this->actingAs($admin)->put(route('admin.vouchers.update', $voucher), [
            'code' => 'CHANGED',
            'type' => Voucher::TYPE_PERCENTAGE,
            'value' => 20,
            'min_purchase' => 50000,
            'max_discount' => 25000,
            'usage_limit' => 2,
            'per_user_limit' => 1,
            'is_active' => 1,
        ])->assertSessionHasErrors('code');
    }

    private function assertVoucherRejected(string $message): void
    {
        try {
            app(VoucherService::class)->validateAndCalculate('SAVE10', 100000, $this->customer);
            $this->fail('Expected voucher validation error.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString($message, $exception->errors()['voucherCode'][0]);
        }
    }
}
