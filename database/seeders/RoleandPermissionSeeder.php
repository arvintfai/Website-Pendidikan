<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleandPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Membuat permission
        Permission::create(['name' => 'develop']);

        // Membuat role dan menetapkan permission ke role tersebut
        Role::create(['name' => 'guest']);
        Role::create(['name' => 'teacher']);
        Role::create(['name' => 'student']);
        $role = Role::create(['name' => 'administrator']);
        $role->givePermissionTo('develop');

        // Menetapkan role ke user
        $user = User::find(1);
        $user->assignRole('administrator');

        // Menetapkan role ke user
        $user = User::find(1);
        $user->assignRole('administrator');
    }
}
