<?php

namespace App\Console\Commands;

use App\Events\InventoryAlertEvent;
use App\Models\InventoryAlertState;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckInventoryAlerts extends Command
{
    protected $signature = 'inventory:check-alerts';

    protected $description = 'Notify admins when an active SKU enters low or out-of-stock state';

    public function handle(): int
    {
        $notified = 0;
        $threshold = (int) Setting::valueFor('inventory_low_stock_threshold', 5);
        $skuIds = ProductSku::query()->where('is_active', true)
            ->whereHas('product', fn ($query) => $query->where('status', Product::STATUS_ACTIVE))
            ->whereHas('variant', fn ($query) => $query->where('is_active', true))
            ->orderBy('id')->pluck('id');

        foreach ($skuIds as $skuId) {
            $sent = DB::transaction(function () use ($skuId, $threshold): bool {
                $sku = ProductSku::query()->with(['stock', 'product', 'variant', 'size'])
                    ->whereKey($skuId)->lockForUpdate()->first();
                if (! $sku || ! $sku->is_active || $sku->product?->status !== Product::STATUS_ACTIVE || ! $sku->variant?->is_active) {
                    return false;
                }

                $available = $sku->stock?->available ?? 0;
                $level = $available === 0 ? 'OUT' : ($available <= $threshold ? 'LOW' : 'OK');
                $state = InventoryAlertState::query()->whereKey($sku->id)->lockForUpdate()->first();
                $previous = $state?->level ?? 'OK';

                if ($state) {
                    $state->update(['level' => $level, 'last_notified_at' => $level !== 'OK' && $level !== $previous ? now() : $state->last_notified_at]);
                } else {
                    InventoryAlertState::create([
                        'product_sku_id' => $sku->id,
                        'level' => $level,
                        'last_notified_at' => $level !== 'OK' ? now() : null,
                    ]);
                }

                if ($level !== 'OK' && $level !== $previous) {
                    event(new InventoryAlertEvent($sku, $level, $available));

                    return true;
                }

                return false;
            });

            $notified += (int) $sent;
        }

        $this->info("Inventory alerts sent for {$notified} SKU state changes.");

        return self::SUCCESS;
    }
}
