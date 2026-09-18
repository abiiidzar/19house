<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    public const TYPE_FIXED = 'FIXED';

    public const TYPE_PERCENTAGE = 'PERCENTAGE';

    protected $fillable = [
        'code', 'type', 'value', 'min_purchase', 'max_discount', 'start_date', 'end_date',
        'usage_limit', 'per_user_limit', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'integer',
            'min_purchase' => 'integer',
            'max_discount' => 'integer',
            'usage_limit' => 'integer',
            'per_user_limit' => 'integer',
            'is_active' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }
}
