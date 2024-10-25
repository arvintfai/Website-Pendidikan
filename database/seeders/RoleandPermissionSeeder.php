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
        // Permission::create(['name' => 'develop']);

        // Membuat role dan menetapkan permission ke role tersebut
        $role = Role::create(['name' => 'guest']);
        // $role->givePermissionTo('develop');

        // // Menetapkan role ke user
        // $user = User::find(2);
        // $user->assignRole('administrator');
    }
}
