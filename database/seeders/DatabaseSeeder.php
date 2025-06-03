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
        // User::factory(10)->create()->each(function ($user) {
        //     $user->syncRoles('student');
        // });

        User::factory()->create([
            'name' => 'admin',
            'email' => 'developer@app.com',
        ]);

        User::factory()->create([
            'name' => 'guru',
            'email' => 'guru',
        ]);

        $this->call(
            RoleandPermissionSeeder::class
        );
    }
}
