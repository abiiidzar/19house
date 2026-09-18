<?php

namespace App\Services\Shipping;

use App\Models\Setting;

class ShippingService
{
    public function getAvailableMethods(): array
    {
        return [
            'REGULAR' => ['name' => 'Regular (3-5 Days)', 'cost' => (int) Setting::valueFor('shipping_regular_cost', 20000)],
            'EXPRESS' => ['name' => 'Express (1-2 Days)', 'cost' => (int) Setting::valueFor('shipping_express_cost', 40000)],
        ];
    }

    public function getCost(string $method): int
    {
        $methods = $this->getAvailableMethods();

        return $methods[$method]['cost'] ?? 0;
    }
}
