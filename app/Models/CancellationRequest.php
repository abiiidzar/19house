<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CancellationRequest extends Model
{
    public const STATUS_REQUESTED = 'REQUESTED';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_REJECTED = 'REJECTED';

    protected $fillable = ['order_id', 'requested_by', 'reason', 'status', 'reviewed_by', 'admin_note', 'requested_at', 'reviewed_at'];

    protected $casts = ['requested_at' => 'datetime', 'reviewed_at' => 'datetime'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
