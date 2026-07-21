<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'nickname' => "sample_user",
            'password' => Hash::make("password"),
            'role' => "student",
        ]);
        // student account
        User::factory()->create([
            'nickname' => 'user123',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // admin account
        User::factory()->create([
            'nickname' => 'admin123',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
