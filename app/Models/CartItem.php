<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_sku_id', 'quantity'];

    protected $casts = ['quantity' => 'integer'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }
}
