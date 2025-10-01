<?php

namespace Database\Seeders;

use App\Models\PolicyAttribute;
use Illuminate\Database\Seeder;

class PolicyAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        PolicyAttribute::factory()->count(20)->create();
    }
}
