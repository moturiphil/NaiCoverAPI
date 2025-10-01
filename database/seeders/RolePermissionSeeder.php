<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'customer']);
        Permission::create(['name' => 'create policy']);
        Permission::create(['name' => 'delete policy']);

        $user = User::find(1);
        $user->assignRole('admin');
        $user->givePermissionTo('create policy');
    }
}
