<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAlertState extends Model
{
    protected $primaryKey = 'product_sku_id';

    public $incrementing = false;

    protected $fillable = ['product_sku_id', 'level', 'last_notified_at'];

    protected function casts(): array
    {
        return ['last_notified_at' => 'datetime'];
    }
}
