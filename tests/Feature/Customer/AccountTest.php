<?php

namespace Tests\Feature\Customer;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Role;
use App\Models\User;
use App\Notifications\OrderUpdate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): User
    {
        $role = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);

        return User::factory()->create(['role_id' => $role->id, 'status' => 'ACTIVE', 'password' => 'OldPassword123']);
    }

    public function test_dashboard_counts_all_customer_orders_and_never_other_customers_orders(): void
    {
        $owner = $this->customer();
        $other = $this->customer();
        Order::factory()->count(6)->create(['user_id' => $owner->id, 'status' => Order::STATUS_PAID]);
        Order::factory()->create(['user_id' => $other->id, 'status' => Order::STATUS_PENDING_PAYMENT]);

        $this->actingAs($owner)->get(route('customer.dashboard'))
            ->assertOk()
            ->assertViewHas('totalOrders', 6)
            ->assertViewHas('processingOrders', 6)
            ->assertViewHas('pendingPayments', 0)
            ->assertSee('Recent Orders');
    }

    public function test_order_policy_blocks_other_customers_and_timeline_uses_real_history(): void
    {
        $owner = $this->customer();
        $other = $this->customer();
        $order = Order::factory()->create(['user_id' => $owner->id, 'status' => Order::STATUS_PAID]);
        OrderStatusHistory::create(['order_id' => $order->id, 'status' => Order::STATUS_PENDING_PAYMENT, 'description' => 'Placed']);
        OrderStatusHistory::create(['order_id' => $order->id, 'status' => Order::STATUS_PAID, 'description' => 'Confirmed']);

        $this->actingAs($other)->get(route('customer.orders.show', $order))->assertForbidden();
        $this->post(route('customer.orders.request-cancellation', $order), ['reason' => 'Not mine'])->assertForbidden();

        $this->actingAs($owner)->get(route('customer.orders.show', $order))
            ->assertOk()
            ->assertSee('Order Timeline')
            ->assertSee('Order Placed')
            ->assertSee('Payment Confirmed')
            ->assertDontSee('Delivered');
    }

    public function test_address_policy_rejects_updates_and_deletes_from_other_customer(): void
    {
        $owner = $this->customer();
        $other = $this->customer();
        $address = CustomerAddress::create([
            'user_id' => $owner->id,
            'recipient_name' => 'Owner',
            'phone' => '081234567890',
            'address_line_1' => 'Owner Street',
            'city' => 'Jakarta',
            'postal_code' => '12345',
            'is_default' => true,
        ]);

        $this->actingAs($other)->put(route('customer.addresses.update', $address), [
            'recipient_name' => 'Attacker', 'phone' => '081234567890', 'address_line_1' => 'Other Street', 'city' => 'Jakarta', 'postal_code' => '12345',
        ])->assertForbidden();
        $this->delete(route('customer.addresses.destroy', $address))->assertForbidden();
        $this->assertSame('Owner', $address->fresh()->recipient_name);
    }

    public function test_notifications_are_owned_and_can_be_marked_read(): void
    {
        $owner = $this->customer();
        $other = $this->customer();
        $owner->notify(new OrderUpdate('Your order shipped', 123));
        $notification = $owner->notifications()->firstOrFail();

        $this->actingAs($other)->post(route('customer.notifications.read', $notification->id))->assertNotFound();
        $this->actingAs($owner)->get(route('customer.notifications.index'))->assertOk()->assertSee('Your order shipped');
        $this->post(route('customer.notifications.read', $notification->id))->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);

        $owner->notify(new OrderUpdate('Second update', 124));
        $this->post(route('customer.notifications.read-all'))->assertRedirect();
        $this->assertSame(0, $owner->unreadNotifications()->count());
    }

    public function test_profile_password_requires_current_password(): void
    {
        $customer = $this->customer();
        $this->actingAs($customer)->put(route('profile.password.update'), [
            'current_password' => 'WrongPassword123', 'password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123',
        ])->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('OldPassword123', $customer->fresh()->password));

        $this->put(route('profile.password.update'), [
            'current_password' => 'OldPassword123', 'password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123',
        ])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('NewPassword123', $customer->fresh()->password));
    }

    public function test_every_active_user_role_can_update_and_remove_a_profile_photo(): void
    {
        Storage::fake('public');

        foreach (['customer', 'admin', 'cashier', 'management'] as $slug) {
            $role = Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug)]);
            $user = User::factory()->create(['role_id' => $role->id, 'status' => 'ACTIVE']);
            $oldPath = "profiles/old-{$slug}.jpg";
            Storage::disk('public')->put($oldPath, 'old-photo');
            $user->update(['profile_photo_path' => $oldPath]);

            $response = $this->actingAs($user)->patch(route('profile.update'), [
                'name' => $user->name,
                'phone' => '081234567890',
                'profile_photo' => UploadedFile::fake()->image("{$slug}.jpg", 320, 320),
            ]);

            $response->assertRedirect()->assertSessionHasNoErrors();
            $newPath = $user->fresh()->profile_photo_path;
            $this->assertNotNull($newPath);
            $this->assertNotSame($oldPath, $newPath);
            Storage::disk('public')->assertExists($newPath);
            Storage::disk('public')->assertMissing($oldPath);

            $this->patch(route('profile.update'), [
                'name' => $user->name,
                'phone' => $user->phone,
                'remove_profile_photo' => true,
            ])->assertRedirect()->assertSessionHasNoErrors();

            $this->assertNull($user->fresh()->profile_photo_path);
            Storage::disk('public')->assertMissing($newPath);
        }
    }

    public function test_account_area_requires_customer_role(): void
    {
        $this->get(route('customer.dashboard'))->assertRedirect(route('login'));

        $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id, 'status' => 'ACTIVE']);
        $this->actingAs($admin)->get(route('customer.dashboard'))->assertForbidden();
        $this->get(route('customer.notifications.index'))->assertForbidden();
        $this->get(route('profile.edit'))->assertOk();
    }
}
