<?php

namespace App\Services\Inventory;

use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use App\Models\ProductSku;
use App\Models\User;
use App\Services\System\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function __construct(private ActivityLogService $activityLogs) {}

    public const TYPE_STOCK_IN = 'STOCK_IN';

    public const TYPE_ADJUSTMENT_IN = 'ADJUSTMENT_IN';

    public const TYPE_ADJUSTMENT_OUT = 'ADJUSTMENT_OUT';

    public const TYPE_RESERVATION = 'RESERVATION';

    public const TYPE_RESERVATION_RELEASED = 'RESERVATION_RELEASED';

    public const TYPE_RESERVATION_CONSUMED = 'RESERVATION_CONSUMED';

    public const TYPE_RESERVATION_EXPIRED = 'RESERVATION_EXPIRED';

    public const TYPE_POS_SALE = 'POS_SALE';

    public function deductStockForPos(ProductSku $sku, int $quantity, string $reference, User $actor): void
    {
        if ($quantity <= 0) {
            throw new Exception('POS quantity must be greater than zero.');
        }

        DB::transaction(function () use ($sku, $quantity, $reference, $actor) {
            $stock = $this->getOrCreateStock($sku);
            if ($stock->available < $quantity) {
                throw new Exception('Insufficient available stock for SKU: '.$sku->sku);
            }

            $before = $stock->on_hand;
            $after = $before - $quantity;
            $stock->update(['on_hand' => $after]);
            $this->recordMovement($sku, self::TYPE_POS_SALE, $quantity, $before, $after, $reference, 'POS sale', $actor);
        });
    }

    public function addStock(ProductSku $sku, int $quantity, ?string $reference = null, ?User $actor = null, ?string $note = null): void
    {
        if ($quantity <= 0) {
            throw new Exception('Quantity must be greater than 0.');
        }

        DB::transaction(function () use ($sku, $quantity, $reference, $actor, $note) {
            $stock = $this->getOrCreateStock($sku);
            $before = $stock->on_hand;
            $after = $before + $quantity;

            $stock->update(['on_hand' => $after]);
            $this->recordMovement($sku, self::TYPE_STOCK_IN, $quantity, $before, $after, $reference, $note, $actor);
            $this->recordActivity($stock, 'inventory.stock_in', $before, $after, $actor, $note);
        });
    }

    public function adjustStock(ProductSku $sku, int $quantity, string $reason, ?User $actor = null): void
    {
        if (empty($reason)) {
            throw new Exception('Reason is mandatory for stock adjustment.');
        }

        DB::transaction(function () use ($sku, $quantity, $reason, $actor) {
            $stock = $this->getOrCreateStock($sku);
            $before = $stock->on_hand;
            $after = $before + $quantity;

            if ($after < 0) {
                throw new Exception('Negative resulting stock forbidden. Current: '.$before.', Trying to deduct: '.abs($quantity));
            }

            $type = $quantity > 0 ? self::TYPE_ADJUSTMENT_IN : self::TYPE_ADJUSTMENT_OUT;

            $stock->update(['on_hand' => $after]);
            $this->recordMovement($sku, $type, $quantity, $before, $after, null, $reason, $actor);
            $this->recordActivity($stock, 'inventory.adjusted', $before, $after, $actor, $reason);
        });
    }

    public function reserveStock(ProductSku $sku, int $quantity, ?string $reference = null, ?User $actor = null): void
    {
        DB::transaction(function () use ($sku, $quantity, $reference, $actor) {
            $stock = $this->getOrCreateStock($sku);
            if ($stock->available < $quantity) {
                throw new Exception('Insufficient available stock for SKU: '.$sku->sku);
            }

            $stock->increment('reserved', $quantity);
            $this->recordMovement($sku, self::TYPE_RESERVATION, $quantity, $stock->on_hand, $stock->on_hand, $reference, 'Stock reserved', $actor);
        });
    }

    public function consumeStock(ProductSku $sku, int $quantity, ?string $reference = null, ?User $actor = null): void
    {
        DB::transaction(function () use ($sku, $quantity, $reference, $actor) {
            $stock = $this->getOrCreateStock($sku);
            $before = $stock->on_hand;

            if ($stock->reserved < $quantity) {
                throw new Exception('Reserved stock is less than quantity to consume.');
            }

            $stock->decrement('reserved', $quantity);
            $stock->decrement('on_hand', $quantity);

            $this->recordMovement($sku, self::TYPE_RESERVATION_CONSUMED, $quantity, $before, $stock->on_hand, $reference, 'Reservation consumed', $actor);
        });
    }

    public function releaseStock(ProductSku $sku, int $quantity, ?string $reference = null, ?User $actor = null): void
    {
        DB::transaction(function () use ($sku, $quantity, $reference, $actor) {
            $stock = $this->getOrCreateStock($sku);

            if ($stock->reserved >= $quantity) {
                $stock->decrement('reserved', $quantity);
                $this->recordMovement($sku, self::TYPE_RESERVATION_RELEASED, $quantity, $stock->on_hand, $stock->on_hand, $reference, 'Reservation released', $actor);
            }
        });
    }

    protected function getOrCreateStock(ProductSku $sku): InventoryStock
    {
        return InventoryStock::where('product_sku_id', $sku->id)
            ->lockForUpdate()
            ->firstOrCreate(
                ['product_sku_id' => $sku->id],
                ['on_hand' => 0, 'reserved' => 0]
            );
    }

    protected function recordMovement(ProductSku $sku, string $type, int $quantity, int $before, int $after, ?string $reference, ?string $reason, ?User $actor): void
    {
        InventoryMovement::create([
            'product_sku_id' => $sku->id,
            'type' => $type,
            'quantity' => $quantity,
            'before' => $before,
            'after' => $after,
            'reference' => $reference,
            'reason' => $reason,
            'actor_id' => $actor?->id,
        ]);
    }

    protected function recordActivity(InventoryStock $stock, string $action, int $before, int $after, ?User $actor, ?string $description): void
    {
        $this->activityLogs->log($action, $stock, ['on_hand' => $before], ['on_hand' => $after], $actor, $description);
    }
}
