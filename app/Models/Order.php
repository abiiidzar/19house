<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'PENDING_PAYMENT';

    public const STATUS_PAID = 'PAID';

    public const STATUS_PROCESSING = 'PROCESSING';

    public const STATUS_READY_TO_SHIP = 'READY_TO_SHIP';

    public const STATUS_SHIPPED = 'SHIPPED';

    public const STATUS_DELIVERED = 'DELIVERED';

    public const STATUS_COMPLETED = 'COMPLETED';

    public const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'order_number', 'user_id',
        'status', 'subtotal',
        'shipping_cost', 'discount',
        'total', 'shipping_method',
        'payment_method',
        'address_snapshot',
    ];

    protected $casts = [
        'address_snapshot' => 'array',
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'discount' => 'integer',
        'total' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    public function cancellationRequests(): HasMany
    {
        return $this->hasMany(CancellationRequest::class);
    }

    public function voucherUsage(): HasOne
    {
        return $this->hasOne(VoucherUsage::class);
    }
}
