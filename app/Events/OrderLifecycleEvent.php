<?php

namespace App\Events;

use App\Models\Order;

class OrderLifecycleEvent
{
    public function __construct(public Order $order, public string $type, public ?string $detail = null) {}
}
