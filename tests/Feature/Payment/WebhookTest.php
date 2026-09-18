<?php

namespace Tests\Feature\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductSku;
use App\Services\Inventory\InventoryService;
use App\Services\Inventory\StockReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_marks_order_as_paid_and_consumes_inventory(): void
    {
        // 1. Setup Data
        $sku = ProductSku::factory()->create();
        $inventoryService = app(InventoryService::class);
        $reservationService = app(StockReservationService::class);

        $inventoryService->addStock($sku, 10, 'INITIAL');

        $order = Order::factory()->create(['status' => Order::STATUS_PENDING_PAYMENT]);
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'status' => Payment::STATUS_PENDING,
            'amount' => $order->total,
        ]);

        // Simulasikan reservasi stok saat checkout
        $reservationService->reserve($sku, 2, 60, $order->id);

        // Pastikan stok awal reserved = 2, on_hand = 10
        $this->assertEquals(2, $sku->fresh()->stock->reserved);
        $this->assertEquals(10, $sku->fresh()->stock->on_hand);

        // 2. Aksi: Simulasiasi Webhook dari Gateway
        $response = $this->postJson(route('webhooks.payments'), [
            'payment_reference' => $payment->payment_reference,
        ]);

        // 3. Assert
        $response->assertStatus(200);

        // Payment dan Order berubah
        $this->assertEquals(Payment::STATUS_PAID, $payment->fresh()->status);
        $this->assertEquals(Order::STATUS_PAID, $order->fresh()->status);

        // Inventory terkonsumsi (reserved berkurang, on_hand berkurang)
        $this->assertEquals(0, $sku->fresh()->stock->reserved);
        $this->assertEquals(8, $sku->fresh()->stock->on_hand);
    }

    public function test_duplicate_webhook_is_safe_and_idempotent(): void
    {
        $sku = ProductSku::factory()->create();
        $inventoryService = app(InventoryService::class);
        $reservationService = app(StockReservationService::class);

        $inventoryService->addStock($sku, 10, 'INITIAL');
        $order = Order::factory()->create(['status' => Order::STATUS_PENDING_PAYMENT]);
        $payment = Payment::factory()->create([
            'order_id' => $order->id, 'status' => Payment::STATUS_PENDING,
        ]);

        $reservationService->reserve($sku, 2, 60, $order->id);

        // Kirim webhook 2x
        $this->postJson(route('webhooks.payments'), ['payment_reference' => $payment->payment_reference]);
        $this->postJson(route('webhooks.payments'), ['payment_reference' => $payment->payment_reference]);

        // Pastikan stok hanya dikurangi sekali (Idempotency)
        $this->assertEquals(0, $sku->fresh()->stock->reserved);
        $this->assertEquals(8, $sku->fresh()->stock->on_hand);
    }
}
