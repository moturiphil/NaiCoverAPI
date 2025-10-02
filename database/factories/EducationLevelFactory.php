<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EducationLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $levels = [
            'High School',
            'Diploma',
            'Bachelor\'s Degree',
            'Master\'s Degree',
            'PhD',
        ];

        static $index = 0;

        $level = $levels[$index % count($levels)];
        $index++;

        return [
            'name' => $level,
        ];
    }

}
