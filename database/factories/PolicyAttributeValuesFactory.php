<?php

namespace Database\Factories;

use App\Models\Policy;
use App\Models\PolicyAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PolicyAttributeValuesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'policy_id' => Policy::factory(),
            'attribute_id' => PolicyAttribute::factory(),
            'value' => $this->faker->text(50),
        ];
    }
}
