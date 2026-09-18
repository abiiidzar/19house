<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherUsage extends Model
{
    public const STATUS_RESERVED = 'RESERVED';

    public const STATUS_REDEEMED = 'REDEEMED';

    public const STATUS_RELEASED = 'RELEASED';

    protected $fillable = [
        'voucher_id', 'order_id', 'user_id', 'discount_amount', 'status', 'redeemed_at', 'released_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_amount' => 'integer',
            'redeemed_at' => 'datetime',
            'released_at' => 'datetime',
        ];
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
