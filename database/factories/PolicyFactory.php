<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Insurance;
use App\Models\PolicyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Policy>
 */
class PolicyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'policy_number' => $this->faker->unique()->bothify('POL#######'),
            'customer_id' => Customer::factory(),
            'provider_id' => Insurance::factory(),
            'policy_type_id' => PolicyType::factory(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'premium_amount' => $this->faker->randomFloat(2, 1000, 100000),
            'status' => $this->faker->randomElement(['active', 'expired', 'cancelled']),
        ];
    }
}
