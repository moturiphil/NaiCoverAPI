<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'payment_reference' => 'PAY-'.$this->faker->unique()->numerify('############'),
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'method' => $this->faker->randomElement(['card', 'mobile_money', 'bank_transfer']),
            'status' => $this->faker->randomElement(['initiated', 'successful', 'failed', 'refunded']),
            'paid_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the payment is successful.
     */
    public function successful(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'successful',
            'paid_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the payment failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'paid_at' => null,
        ]);
    }

    /**
     * Indicate that the payment is initiated.
     */
    public function initiated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'initiated',
            'paid_at' => null,
        ]);
    }
}
