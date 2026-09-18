<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\CancellationRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Services\Payment\PaymentService;
use App\Services\Shipping\ShippingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSystemPagesTest extends TestCase
{
    use RefreshDatabase;

    private function account(string $role): User
    {
        return User::factory()->create(['role_id' => Role::factory()->create(['slug' => $role])->id, 'status' => 'ACTIVE']);
    }

    public function test_only_admin_can_access_system_pages(): void
    {
        $admin = $this->account('admin');
        $customer = $this->account('customer');

        foreach (['admin.users.index', 'admin.settings.index', 'admin.payments.index', 'admin.inventory.stock-in.create', 'admin.inventory.adjustment.create', 'admin.inventory.movements.index', 'admin.cancellations.index', 'admin.customers.index', 'admin.reports.sales', 'admin.roles.index'] as $route) {
            $this->actingAs($customer)->get(route($route))->assertForbidden();
            $this->actingAs($admin)->get(route($route))->assertOk();
        }
    }

    public function test_admin_can_create_user_but_cannot_remove_own_access(): void
    {
        $admin = $this->account('admin');
        $cashier = Role::factory()->create(['slug' => 'cashier']);

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Cashier One', 'email' => 'cashier-one@example.test',
            'role_id' => $cashier->id, 'status' => 'ACTIVE',
            'password' => 'SafePass123', 'password_confirmation' => 'SafePass123',
        ])->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'cashier-one@example.test', 'role_id' => $cashier->id]);

        $this->put(route('admin.users.update', $admin), [
            'name' => $admin->name, 'email' => $admin->email,
            'role_id' => $cashier->id, 'status' => 'INACTIVE',
        ])->assertSessionHasErrors('role_id');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'status' => 'ACTIVE']);
    }

    public function test_settings_changes_are_audited(): void
    {
        $admin = $this->account('admin');
        $this->actingAs($admin)->put(route('admin.settings.update'), [
            'store_name' => '19HOUSE', 'support_email' => 'help@example.test', 'support_phone' => '0812345678',
        ])->assertRedirect();
        $this->assertSame('help@example.test', Setting::where('key', 'support_email')->value('value'));
        $this->assertTrue(ActivityLog::where('action', 'system.setting.created')->exists());

        $this->put(route('admin.settings.update'), [
            'store_name' => '19HOUSE', 'support_email' => 'new@example.test', 'support_phone' => '0812345678',
        ])->assertRedirect();
        $this->assertTrue(ActivityLog::where('action', 'system.setting.updated')->exists());
    }

    public function test_payment_list_can_be_filtered(): void
    {
        $admin = $this->account('admin');
        Payment::factory()->create(['status' => Payment::STATUS_PAID]);
        $this->actingAs($admin)->get(route('admin.payments.index', ['status' => Payment::STATUS_PAID]))->assertOk()->assertSee('PAID');
        $this->get(route('admin.payments.index', ['status' => 'INVALID']))->assertNotFound();
    }

    public function test_dashboard_and_new_admin_pages_render_real_data(): void
    {
        $admin = $this->account('admin');
        $customer = $this->account('customer');
        $order = Order::factory()->create(['user_id' => $customer->id, 'status' => Order::STATUS_PAID]);
        $payment = Payment::factory()->create(['order_id' => $order->id, 'status' => Payment::STATUS_PAID, 'provider_payment_id' => 'PROVIDER-42']);
        $payment->events()->create(['event_type' => 'PAID', 'payload' => ['message' => 'Confirmed']]);
        $order->cancellationRequests()->create(['requested_by' => $customer->id, 'reason' => 'Changed mind', 'status' => CancellationRequest::STATUS_REQUESTED, 'requested_at' => now()]);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertSee('Orders to fulfill')->assertSee('Recent activity');
        $this->get(route('admin.payments.show', $payment))->assertOk()->assertSee('PROVIDER-42')->assertSee('BANK_TRANSFER')->assertSee('Paid at');
        $this->get(route('admin.cancellations.index'))->assertOk()->assertSee('Changed mind');
        $this->get(route('admin.customers.index'))->assertOk()->assertSee($customer->email);
        $this->get(route('admin.customers.show', $customer))->assertOk()->assertSee($order->order_number);
        $this->get(route('admin.reports.sales'))->assertOk()->assertSee('Sales Report');
        $this->get(route('admin.reports.products'))->assertOk()->assertSee('Product Report');
        $this->get(route('admin.reports.inventory'))->assertOk()->assertSee('Inventory Report');
        $this->get(route('admin.reports.customers'))->assertOk()->assertSee('Customer Report');
    }

    public function test_settings_are_used_by_shipping_and_new_payments(): void
    {
        $admin = $this->account('admin');
        $this->actingAs($admin)->put(route('admin.settings.update'), [
            'store_name' => '19HOUSE', 'shipping_regular_cost' => 25000,
            'shipping_express_cost' => 45000, 'inventory_low_stock_threshold' => 8,
            'payment_expiry_minutes' => 30,
        ])->assertRedirect();

        $this->assertSame(25000, app(ShippingService::class)->getCost('REGULAR'));
        $order = Order::factory()->create();
        $payment = app(PaymentService::class)->createPaymentAttempt($order);
        $this->assertTrue($payment->expires_at->between(now()->addMinutes(29), now()->addMinutes(31)));
    }

    public function test_internal_users_are_separate_from_customers_and_role_permissions_are_audited(): void
    {
        $admin = $this->account('admin');
        $customer = $this->account('customer');
        $cashier = Role::factory()->create(['slug' => 'cashier']);
        $permission = Permission::create(['name' => 'POS access', 'slug' => 'pos.access']);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk()->assertDontSee($customer->email);
        $this->get(route('admin.users.edit', $customer))->assertNotFound();
        $this->get(route('admin.roles.edit', $cashier))->assertOk()->assertSee('POS access');
        $this->put(route('admin.roles.update', $cashier), ['permissions' => [$permission->id]])->assertRedirect(route('admin.roles.index'));
        $this->assertTrue($cashier->permissions()->whereKey($permission->id)->exists());
        $this->assertTrue(ActivityLog::where('action', 'user.role_permissions_changed')->exists());
    }
}
