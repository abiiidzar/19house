<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_sku_id',
        'type',
        'quantity',
        'before',
        'after',
        'reference',
        'reason',
        'actor_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'before' => 'integer',
        'after' => 'integer',
    ];

    public function sku(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
