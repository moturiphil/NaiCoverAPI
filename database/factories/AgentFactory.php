<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'police_clearance' => $this->faker->boolean(70), 
            'police_clearance_path' => $this->faker->optional()->filePath(),
            'cv_path' => $this->faker->optional()->filePath(),
            'id_path' => $this->faker->optional()->filePath(),
            'passport_photo_path' => $this->faker->optional()->filePath(),
            'kcse_certificate_path' => $this->faker->optional()->filePath(),
            'diploma_certificate_path' => $this->faker->optional()->filePath(),
            'degree_certificate_path' => $this->faker->optional()->filePath(),
        ];
    }
}
