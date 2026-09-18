<?php

namespace App\Services\Inventory;

use App\Models\InventoryReservation;
use App\Models\ProductSku;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;

class StockReservationService
{
    public function __construct(protected InventoryService $inventoryService) {}

    public function reserve(ProductSku $sku, int $quantity, int $ttlMinutes = 15, Order|int|null $order = null): InventoryReservation
    {
        if ($quantity <= 0) {
            throw new Exception('Quantity to reserve must be positive.');
        }

        return DB::transaction(function () use ($sku, $quantity, $ttlMinutes, $order) {
            $orderId = $order instanceof Order ? $order->id : $order;

            // Lock inventory dulu via service
            $this->inventoryService->reserveStock($sku, $quantity, 'RESERVATION_HOLD');

            return InventoryReservation::create([
                'product_sku_id' => $sku->id,
                'order_id' => $orderId,
                'quantity' => $quantity,
                'status' => 'PENDING',
                'expires_at' => now()->addMinutes($ttlMinutes),
            ]);
        });
    }

    public function consume(InventoryReservation $reservation): void
    {
        DB::transaction(function () use ($reservation) {
            $reservation = InventoryReservation::lockForUpdate()->findOrFail($reservation->id);

            if ($reservation->status !== 'PENDING') {
                throw new Exception('Only PENDING reservations can be consumed.');
            }

            if ($reservation->expires_at?->isPast()) {
                throw new Exception('Expired reservations cannot be consumed.');
            }

            $this->inventoryService->consumeStock($reservation->sku, $reservation->quantity, 'ORDER_'.$reservation->id);
            $reservation->update(['status' => 'CONSUMED']);
        });
    }

    public function consumeForOrder(Order $order, ProductSku $sku): void
    {
        $reservation = InventoryReservation::where('product_sku_id', $sku->id)
            ->where('order_id', $order->id)
            ->where('status', 'PENDING')
            ->firstOrFail();

        $this->consume($reservation);
    }

    public function releaseForOrder(Order $order, ProductSku $sku): void
    {
        $reservation = InventoryReservation::where('product_sku_id', $sku->id)
            ->where('order_id', $order->id)
            ->where('status', 'PENDING')
            ->first();

        if ($reservation) {
            $this->release($reservation);
        }
    }

    public function release(InventoryReservation $reservation): void
    {
        DB::transaction(function () use ($reservation) {
            $reservation = InventoryReservation::lockForUpdate()->findOrFail($reservation->id);

            if ($reservation->status !== 'PENDING') {
                return;
            }

            $this->inventoryService->releaseStock($reservation->sku, $reservation->quantity, 'CANCELLED_'.$reservation->id);
            $reservation->update(['status' => 'RELEASED']);
        });
    }

    public function expire(InventoryReservation $reservation): void
    {
        DB::transaction(function () use ($reservation) {
            $reservation = InventoryReservation::lockForUpdate()->findOrFail($reservation->id);

            if ($reservation->status !== 'PENDING' || $reservation->expires_at?->isFuture()) {
                return;
            }

            $this->inventoryService->releaseStock($reservation->sku, $reservation->quantity, 'EXPIRED_'.$reservation->id);
            $reservation->update(['status' => 'EXPIRED']);
        });
    }
}
