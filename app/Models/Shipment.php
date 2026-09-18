<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = ['order_id', 'courier', 'service', 'tracking_number', 'shipping_cost', 'status', 'shipped_at', 'delivered_at', 'metadata'];

    protected $casts = ['shipped_at' => 'datetime', 'delivered_at' => 'datetime', 'metadata' => 'array'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
