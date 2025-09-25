<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'police_clearance' => $this->faker->boolean(),
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
