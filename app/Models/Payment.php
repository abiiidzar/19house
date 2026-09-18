<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_PAID = 'PAID';

    public const STATUS_EXPIRED = 'EXPIRED';

    public const STATUS_FAILED = 'FAILED';

    public const STATUS_CANCELLED = 'CANCELLED';

    public const STATUS_REFUND_PENDING = 'REFUND_PENDING';

    public const STATUS_REFUNDED = 'REFUNDED';

    protected $fillable = ['order_id', 'provider', 'provider_payment_id', 'payment_reference', 'amount', 'status', 'expires_at'];

    protected $casts = ['amount' => 'integer', 'expires_at' => 'datetime'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(PaymentEvent::class);
    }
}
