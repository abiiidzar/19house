<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'provider' => 'MOCK',
            'provider_payment_id' => null,
            'payment_reference' => '19PAY-'.strtoupper(Str::random(12)),
            'amount' => 120000,
            'status' => Payment::STATUS_PENDING,
            'expires_at' => now()->addHour(),
        ];
    }
}
