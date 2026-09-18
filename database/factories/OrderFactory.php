<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => '19H-'.fake()->unique()->numerify('######'),
            'user_id' => User::factory(),
            'status' => Order::STATUS_PENDING_PAYMENT,
            'subtotal' => 100000,
            'shipping_cost' => 20000,
            'discount' => 0,
            'total' => 120000,
            'shipping_method' => 'REGULAR',
            'payment_method' => 'BANK_TRANSFER',
            'address_snapshot' => [
                'recipient_name' => fake()->name(),
                'phone' => '081234567890',
                'address_line_1' => fake()->address(),
                'city' => 'Jakarta',
                'postal_code' => '12345',
            ],
        ];
    }
}