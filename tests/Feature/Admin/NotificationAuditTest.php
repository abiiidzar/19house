<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\InventoryStock;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Size;
use App\Models\User;
use App\Models\Voucher;
use App\Services\Inventory\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationAuditTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        $roleModel = Role::firstOrCreate(['slug' => $role], ['name' => ucfirst($role)]);

        return User::factory()->create(['role_id' => $roleModel->id, 'status' => 'ACTIVE']);
    }

    public function test_payment_and_order_events_notify_the_right_people_once(): void
    {
        $admin = $this->user('admin');
        $customer = $this->user('customer');
        $order = Order::factory()->create(['user_id' => $customer->id]);
        $payment = Payment::factory()->create(['order_id' => $order->id, 'status' => Payment::STATUS_PENDING]);

        $this->postJson(route('webhooks.payments'), ['payment_reference' => $payment->payment_reference])->assertOk();
        $this->postJson(route('webhooks.payments'), ['payment_reference' => $payment->payment_reference])->assertOk();

        $this->assertCount(1, $customer->notifications);
        $this->assertCount(1, $admin->notifications);
        $this->assertSame($order->id, $customer->notifications->first()->data['order_id']);
        $this->assertSame(route('admin.orders.show', $order), $admin->notifications->first()->data['url']);

        $this->actingAs($admin)->post(route('admin.orders.advance', $order), ['status' => Order::STATUS_PROCESSING])->assertSessionHasNoErrors();
        $this->assertCount(2, $customer->fresh()->notifications);
    }

    public function test_admin_notification_read_actions_are_scoped_to_owner(): void
    {
        $admin = $this->user('admin');
        $otherAdmin = $this->user('admin');
        $customer = $this->user('customer');
        $order = Order::factory()->create(['user_id' => $customer->id]);
        $payment = Payment::factory()->create(['order_id' => $order->id]);
        $this->postJson(route('webhooks.payments'), ['payment_reference' => $payment->payment_reference])->assertOk();

        $notification = $admin->notifications()->firstOrFail();
        $this->actingAs($otherAdmin)->post(route('admin.notifications.read', $notification->id))->assertNotFound();
        $this->actingAs($admin)->get(route('admin.notifications.index'))->assertOk()->assertSee('New paid order');
        $this->post(route('admin.notifications.read', $notification->id))->assertSessionHasNoErrors();
        $this->assertNotNull($notification->fresh()->read_at);

        $this->actingAs($otherAdmin)->post(route('admin.notifications.read-all'))->assertSessionHasNoErrors();
        $this->assertSame(0, $otherAdmin->unreadNotifications()->count());
        $this->get(route('admin.activity-logs.index'))->assertOk();
        $this->actingAs($customer)->get(route('admin.notifications.index'))->assertForbidden();
    }

    public function test_inventory_command_uses_available_stock_and_only_notifies_on_state_change(): void
    {
        $admin = $this->user('admin');
        $product = Product::factory()->create(['status' => Product::STATUS_ACTIVE]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'is_active' => true]);
        $sku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'size_id' => Size::factory()->create()->id,
            'is_active' => true,
        ]);
        $stock = InventoryStock::create(['product_sku_id' => $sku->id, 'on_hand' => 8, 'reserved' => 4]);

        $this->artisan('inventory:check-alerts')->assertSuccessful();
        $this->assertCount(1, $admin->notifications);
        $this->assertSame('Low stock', $admin->notifications->first()->data['title']);
        $this->artisan('inventory:check-alerts')->assertSuccessful();
        $this->assertCount(1, $admin->fresh()->notifications);

        $stock->update(['on_hand' => 4]);
        $this->artisan('inventory:check-alerts')->assertSuccessful();
        $this->assertCount(2, $admin->fresh()->notifications);
        $this->assertTrue($admin->fresh()->notifications->contains(fn ($notification) => $notification->data['title'] === 'Critical inventory: out of stock'));

        $stock->update(['on_hand' => 12]);
        $this->artisan('inventory:check-alerts')->assertSuccessful();
        $stock->update(['on_hand' => 8]);
        $this->artisan('inventory:check-alerts')->assertSuccessful();
        $this->assertCount(3, $admin->fresh()->notifications);
    }

    public function test_cancellation_request_and_expired_payment_generate_relevant_notifications(): void
    {
        $admin = $this->user('admin');
        $customer = $this->user('customer');
        $paidOrder = Order::factory()->create(['user_id' => $customer->id, 'status' => Order::STATUS_PAID]);
        Payment::factory()->create(['order_id' => $paidOrder->id, 'status' => Payment::STATUS_PAID]);

        $this->actingAs($customer)->post(route('customer.orders.request-cancellation', $paidOrder), ['reason' => 'Wrong item'])->assertSessionHasNoErrors();
        $this->assertTrue($admin->fresh()->notifications->contains(fn ($notification) => $notification->data['title'] === 'New cancellation request'));

        $request = $paidOrder->cancellationRequests()->firstOrFail();
        $this->actingAs($admin)->post(route('admin.cancellation-requests.review', $request), ['decision' => 'reject'])->assertSessionHasNoErrors();
        $this->assertTrue($customer->fresh()->notifications->contains(fn ($notification) => str_contains($notification->data['message'], 'was rejected')));
        $reviewLog = ActivityLog::where('action', 'cancellation.updated')->latest('id')->firstOrFail();
        $this->assertSame('REQUESTED', $reviewLog->old_values['status']);
        $this->assertSame('REJECTED', $reviewLog->new_values['status']);

        $expiredOrder = Order::factory()->create(['user_id' => $customer->id]);
        Payment::factory()->create(['order_id' => $expiredOrder->id, 'expires_at' => now()->subMinute()]);
        $this->artisan('payments:expire')->assertSuccessful();
        $this->assertTrue($customer->fresh()->notifications->contains(fn ($notification) => $notification->data['order_id'] === $expiredOrder->id && str_contains($notification->data['message'], 'expired')));
    }

    public function test_critical_mutations_have_old_and_new_values_without_passwords(): void
    {
        $admin = $this->user('admin');
        $customer = $this->user('customer');
        $this->actingAs($admin);
        $product = Product::factory()->create(['base_price' => 100000]);
        $product->update(['base_price' => 120000]);
        $priceLog = ActivityLog::where('action', 'catalog.price_changed')->latest('id')->firstOrFail();
        $this->assertSame(100000, (int) $priceLog->old_values['base_price']);
        $this->assertSame(120000, (int) $priceLog->new_values['base_price']);
        $this->assertSame($admin->id, $priceLog->user_id);

        $voucher = Voucher::create(['code' => 'AUDIT10', 'type' => 'PERCENTAGE', 'value' => 10, 'per_user_limit' => 1]);
        $voucher->update(['value' => 15]);
        $voucherLog = ActivityLog::where('action', 'promotion.voucher.updated')->latest('id')->firstOrFail();
        $this->assertSame(10, (int) $voucherLog->old_values['value']);
        $this->assertSame(15, (int) $voucherLog->new_values['value']);

        $customer->update(['role_id' => $admin->role_id, 'password' => 'Secret1234']);
        $roleLog = ActivityLog::where('action', 'user.role_changed')->latest('id')->firstOrFail();
        $this->assertSame($admin->role_id, (int) $roleLog->new_values['role_id']);
        $this->assertArrayNotHasKey('password', $roleLog->new_values);

        $sku = ProductSku::factory()->create();
        app(InventoryService::class)->addStock($sku, 5, 'TEST', $admin);
        app(InventoryService::class)->adjustStock($sku, -2, 'Audit test', $admin);
        $stockLog = ActivityLog::where('action', 'inventory.adjusted')->latest('id')->firstOrFail();
        $this->assertSame(5, $stockLog->old_values['on_hand']);
        $this->assertSame(3, $stockLog->new_values['on_hand']);

        $order = Order::factory()->create(['user_id' => $customer->id]);
        $order->update(['status' => Order::STATUS_CANCELLED]);
        $this->assertDatabaseHas('activity_logs', ['action' => 'order.status_changed', 'subject_id' => $order->id]);
    }
}
