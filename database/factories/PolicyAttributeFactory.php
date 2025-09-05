<?php

namespace Database\Factories;

use App\Models\PolicyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PolicyAttribute>
 */
class PolicyAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'policy_type_id' => PolicyType::factory(),
            'name' => $this->faker->unique()->word(),
            'label' => $this->faker->sentence(2),
            'field_type' => $this->faker->randomElement(['text', 'number', 'date', 'select']),
        ];
    }
}
