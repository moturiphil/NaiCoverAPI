<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\EducationLevel;
use App\Models\ExperienceLevel;

/**
 * @extends Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'education_level_id' => EducationLevel::inRandomOrder()->first()->id,
            'experience_level_id' => ExperienceLevel::inRandomOrder()->first()->id,
            'area_of_operation' => $this->faker->city(),
            'police_clearance_path' => $this->faker->filePath(),
            'cv_path' => $this->faker->filePath(),
            'id_number' => fake()->unique()->numerify('########'),
            'id_path' => $this->faker->filePath(),
            'passport_photo_path' => $this->faker->filePath(),
            // 'kcse_certificate_path' => $this->faker->optional()->filePath(),
            'diploma_certificate_path' => $this->faker->filePath(),
            'degree_certificate_path' => $this->faker->filePath(),
            'ira_certificate' => $this->faker->filePath(),
        ];
    }
};
