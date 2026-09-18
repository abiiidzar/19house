<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PosTransaction extends Model
{
    public const STATUS_COMPLETED = 'COMPLETED';

    public const PAYMENT_CONFIRMED = 'CONFIRMED';

    protected $fillable = [
        'receipt_number', 'cashier_id', 'subtotal', 'discount', 'total',
        'payment_method', 'payment_status', 'payment_reference', 'payment_confirmed_at',
        'amount_received', 'change', 'status',
    ];

    protected $casts = [
        'subtotal' => 'integer', 'discount' => 'integer', 'total' => 'integer',
        'amount_received' => 'integer', 'change' => 'integer',
        'payment_confirmed_at' => 'datetime',
    ];

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PosTransactionItem::class);
    }
}
