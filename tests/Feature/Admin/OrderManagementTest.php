<?php

namespace Tests\Feature\Admin;

use App\Models\CancellationRequest;
use App\Models\InventoryReservation;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductSku;
use App\Models\Role;
use App\Models\User;
use App\Services\Inventory\InventoryService;
use App\Services\Inventory\StockReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        $roleModel = Role::firstOrCreate(['slug' => $role], ['name' => ucfirst($role)]);

        return User::factory()->create(['role_id' => $roleModel->id, 'status' => 'ACTIVE']);
    }

    public function test_paid_order_advances_only_in_sequence_and_shipment_notifies_customer(): void
    {
        $admin = $this->user('admin');
        $customer = $this->user('customer');
        $order = Order::factory()->create(['user_id' => $customer->id, 'status' => Order::STATUS_PAID]);
        Payment::factory()->create(['order_id' => $order->id, 'status' => Payment::STATUS_PAID]);

        $this->actingAs($customer)->get(route('admin.orders.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.orders.index', ['status' => 'PAID']))->assertOk()->assertSee($order->order_number);
        $this->get(route('admin.orders.show', $order))->assertOk()->assertSee($order->order_number);
        $this->post(route('admin.orders.advance', $order), ['status' => Order::STATUS_DELIVERED])->assertSessionHasErrors('status');
        $this->assertSame(Order::STATUS_PAID, $order->fresh()->status);

        $this->post(route('admin.orders.advance', $order), ['status' => Order::STATUS_PROCESSING])->assertSessionHasNoErrors();
        $this->post(route('admin.orders.advance', $order), ['status' => Order::STATUS_READY_TO_SHIP])->assertSessionHasNoErrors();
        $this->post(route('admin.orders.ship', $order), ['courier' => 'JNE', 'service' => 'REG', 'tracking_number' => 'JNE12345'])->assertSessionHasNoErrors();
        $this->assertSame(Order::STATUS_SHIPPED, $order->fresh()->status);
        $this->assertSame('JNE12345', $order->fresh()->shipment->tracking_number);
        $this->assertCount(2, $customer->notifications);
        $this->assertTrue($customer->notifications->contains(fn ($notification) => str_contains($notification->data['message'], 'JNE12345')));

        $this->actingAs($customer)->get(route('customer.orders.show', $order))->assertOk()->assertSee('JNE12345');
        $this->actingAs($admin)->post(route('admin.orders.advance', $order), ['status' => Order::STATUS_DELIVERED])->assertSessionHasNoErrors();
        $this->post(route('admin.orders.advance', $order), ['status' => Order::STATUS_COMPLETED])->assertSessionHasNoErrors();
        $this->assertSame(Order::STATUS_COMPLETED, $order->fresh()->status);
        $this->assertCount(3, $customer->fresh()->notifications);
        $this->assertTrue($customer->fresh()->notifications->contains(fn ($notification) => str_contains($notification->data['message'], 'is complete')));
        $this->assertCount(5, $order->histories);
    }

    public function test_customer_can_cancel_unpaid_order_and_release_reserved_stock(): void
    {
        $customer = $this->user('customer');
        $order = Order::factory()->create(['user_id' => $customer->id]);
        $payment = Payment::factory()->create(['order_id' => $order->id]);
        $sku = ProductSku::factory()->create();
        app(InventoryService::class)->addStock($sku, 5);
        app(StockReservationService::class)->reserve($sku, 2, 60, $order);

        $this->actingAs($customer)->post(route('customer.orders.cancel', $order), ['reason' => 'Changed my mind'])->assertSessionHasNoErrors();
        $this->assertSame(Order::STATUS_CANCELLED, $order->fresh()->status);
        $this->assertSame(Payment::STATUS_CANCELLED, $payment->fresh()->status);
        $this->assertSame(0, $sku->fresh()->stock->reserved);
        $this->assertSame(5, $sku->fresh()->stock->on_hand);
        $this->assertSame('RELEASED', InventoryReservation::where('order_id', $order->id)->firstOrFail()->status);
    }

    public function test_paid_cancellation_requires_review_and_restocks_once_with_manual_refund_pending(): void
    {
        $admin = $this->user('admin');
        $customer = $this->user('customer');
        $order = Order::factory()->create(['user_id' => $customer->id, 'status' => Order::STATUS_PAID]);
        $payment = Payment::factory()->create(['order_id' => $order->id, 'status' => Payment::STATUS_PAID]);
        $sku = ProductSku::factory()->create();
        app(InventoryService::class)->addStock($sku, 5);
        $reservation = app(StockReservationService::class)->reserve($sku, 2, 60, $order);
        app(StockReservationService::class)->consume($reservation);
        $this->assertSame(3, $sku->fresh()->stock->on_hand);

        $this->actingAs($customer)->post(route('customer.orders.request-cancellation', $order), ['reason' => 'Wrong item'])->assertSessionHasNoErrors();
        $request = CancellationRequest::where('order_id', $order->id)->firstOrFail();
        $this->assertSame(Order::STATUS_PAID, $order->fresh()->status);
        $this->actingAs($admin)->post(route('admin.orders.advance', $order), ['status' => Order::STATUS_PROCESSING])->assertSessionHasErrors('status');
        $this->post(route('admin.cancellation-requests.review', $request), ['decision' => 'approve', 'admin_note' => 'Approved'])->assertSessionHasNoErrors();
        $this->assertSame(Order::STATUS_CANCELLED, $order->fresh()->status);
        $this->assertSame(Payment::STATUS_REFUND_PENDING, $payment->fresh()->status);
        $this->assertSame(5, $sku->fresh()->stock->on_hand);
        $this->post(route('admin.cancellation-requests.review', $request), ['decision' => 'approve'])->assertSessionHasErrors('status');
        $this->assertSame(5, $sku->fresh()->stock->on_hand);

        $this->post(route('admin.orders.confirm-refund', $order), ['reference' => 'BANK-REF-123'])->assertSessionHasNoErrors();
        $this->assertSame(Payment::STATUS_REFUNDED, $payment->fresh()->status);
        $this->post(route('admin.orders.confirm-refund', $order), ['reference' => 'BANK-REF-123'])->assertSessionHasErrors('refund');
    }

    public function test_other_customer_cannot_view_or_cancel_an_order_and_rejection_keeps_it_paid(): void
    {
        $admin = $this->user('admin');
        $owner = $this->user('customer');
        $other = $this->user('customer');
        $order = Order::factory()->create(['user_id' => $owner->id, 'status' => Order::STATUS_PAID]);
        $payment = Payment::factory()->create(['order_id' => $order->id, 'status' => Payment::STATUS_PAID]);

        $this->actingAs($other)->get(route('customer.orders.show', $order))->assertForbidden();
        $this->post(route('customer.orders.request-cancellation', $order), ['reason' => 'Not mine'])->assertForbidden();

        $this->actingAs($owner)->post(route('customer.orders.request-cancellation', $order), ['reason' => 'Change of plan'])->assertSessionHasNoErrors();
        $request = CancellationRequest::where('order_id', $order->id)->firstOrFail();
        $this->actingAs($admin)->post(route('admin.cancellation-requests.review', $request), ['decision' => 'reject', 'admin_note' => 'Already packed'])->assertSessionHasNoErrors();

        $this->assertSame(CancellationRequest::STATUS_REJECTED, $request->fresh()->status);
        $this->assertSame(Order::STATUS_PAID, $order->fresh()->status);
        $this->assertSame(Payment::STATUS_PAID, $payment->fresh()->status);
        $this->post(route('admin.orders.advance', $order), ['status' => Order::STATUS_PROCESSING])->assertSessionHasNoErrors();
    }
}
