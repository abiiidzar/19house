<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentEvent extends Model
{
    protected $fillable = ['payment_id', 'event_type', 'payload'];

    protected $casts = ['payload' => 'array'];
}
