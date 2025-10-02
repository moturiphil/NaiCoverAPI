<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExperienceLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $levels = [
            'No Experience',
            '1-2 Years',
            '3-5 Years',
            '5+ Years',
        ];

        static $index = 0;

        $level = $levels[$index % count($levels)];
        $index++;

        return [
            'name' => $level,
        ];
    }
}
