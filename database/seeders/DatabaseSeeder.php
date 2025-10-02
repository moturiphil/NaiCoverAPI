<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // call the RolePermissionSeeder
        // $this->call(UserSeeder::class);
        // $this->call(RolePermissionSeeder::class);
        $this->call([
            UserSeeder::class,
            EducationLevelSeeder::class,
            ExperienceLevelSeeder::class,
            AgentSeeder::class,
            PolicyTypeSeeder::class,
            PolicyAttributeSeeder::class,
            CustomerSeeder::class,
            InsuranceSeeder::class,
            PolicySeeder::class,
            PolicyAttributeValueSeeder::class,
        ]);
    }
}
