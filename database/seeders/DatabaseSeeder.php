<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an Admin user
        User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin 123'),
            'role' => 'admin',
        ]);

        // Create a Driver user
        User::factory()->create([
            'name' => 'John Driver',
            'email' => 'driver@test.com',
            'password' => bcrypt('password'),
            'role' => 'driver',
        ]);
    }
}
