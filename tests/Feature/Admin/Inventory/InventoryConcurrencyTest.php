<?php

namespace Tests\Feature\Admin\Inventory;

use App\Models\ActivityLog;
use App\Models\InventoryMovement;
use App\Models\ProductSku;
use App\Services\Inventory\InventoryService;
use App\Services\Inventory\StockReservationService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_concurrent_requests_do_not_oversell(): void
    {
        $sku = ProductSku::factory()->create();
        $inventoryService = app(InventoryService::class);
        $reservationService = app(StockReservationService::class);

        $inventoryService->addStock($sku, 1, 'INITIAL');

        $reservationA = $reservationService->reserve($sku, 1);

        try {
            $reservationService->reserve($sku, 1);
            $this->fail('Overselling terdeteksi: Reservation berhasil dibuat melebihi stok.');
        } catch (Exception $e) {
            $this->assertEquals('Insufficient available stock for SKU: '.$sku->sku, $e->getMessage());
        }

        $this->assertDatabaseHas('inventory_reservations', [
            'product_sku_id' => $sku->id,
            'status' => 'PENDING',
        ]);

        $this->assertEquals(1, $sku->fresh()->stock->reserved);
        $this->assertEquals(0, $sku->fresh()->stock->available);
    }

    public function test_expired_reservation_releases_stock_and_is_audited(): void
    {
        $sku = ProductSku::factory()->create();
        $inventoryService = app(InventoryService::class);
        $reservationService = app(StockReservationService::class);

        $inventoryService->addStock($sku, 2, 'INITIAL');
        $reservation = $reservationService->reserve($sku, 1);
        $reservation->update(['expires_at' => now()->subMinute()]);

        $reservationService->expire($reservation);

        $this->assertSame('EXPIRED', $reservation->fresh()->status);
        $this->assertSame(0, $sku->fresh()->stock->reserved);
        $this->assertTrue(InventoryMovement::where('type', InventoryService::TYPE_RESERVATION_RELEASED)->exists());
        $this->assertTrue(ActivityLog::where('action', 'inventory.stock_in')->exists());
    }
}
