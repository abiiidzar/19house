<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStock extends Model
{
    protected $fillable = [
        'product_sku_id',
        'on_hand',
        'reserved',
    ];

    protected $casts = [
        'on_hand' => 'integer',
        'reserved' => 'integer',
    ];

    public function sku(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }

    // Accessor untuk Available Stock
    public function getAvailableAttribute(): int
    {
        return max(0, $this->on_hand - $this->reserved);
    }
}
