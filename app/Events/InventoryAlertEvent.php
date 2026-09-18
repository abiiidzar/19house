<?php

namespace App\Events;

use App\Models\ProductSku;

class InventoryAlertEvent
{
    public function __construct(public ProductSku $sku, public string $level, public int $available) {}
}
