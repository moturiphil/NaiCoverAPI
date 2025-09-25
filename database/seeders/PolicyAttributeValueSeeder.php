<?php

namespace Database\Seeders;

use App\Models\PolicyAttributeValues;
use Illuminate\Database\Seeder;

class PolicyAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        PolicyAttributeValues::factory()->count(20)->create();
    }
}
