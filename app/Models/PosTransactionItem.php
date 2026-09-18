<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosTransactionItem extends Model
{
    protected $fillable = ['pos_transaction_id', 'product_sku_id', 'price', 'quantity', 'sku_snapshot'];

    protected $casts = ['price' => 'integer', 'quantity' => 'integer', 'sku_snapshot' => 'array'];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(PosTransaction::class, 'pos_transaction_id');
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }
}
