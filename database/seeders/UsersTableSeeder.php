<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing users (optional - only for development)
        // User::truncate();

        // Create admin accounts
        $admins = [
            [
                'name' => 'Cedric Vila',
                'email' => 'lordcedricvila@gmail.com',
                'password' => Hash::make('Cedric123!'),
                'role' => 'admin',
                'age' => 20,
                'sex' => 'male',
                'contact_number' => '09937994369',
                'address' => 'east rembo',
                'status' => 'active',
            ],
            [
                'name' => 'Dwayne Casay',
                'email' => 'dwaynecasay999@gmail.com',
                'password' => Hash::make('Dwayne123!'),
                'role' => 'admin',
                'age' => 20,
                'sex' => 'male',
                'contact_number' => '09123456789',
                'address' => 'pinagsama',
                'status' => 'active',
            ]
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }

        // Create specific user accounts with Password123!
        $users = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@example.com',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'age' => 28,
                'sex' => 'male',
                'contact_number' => '09123456781',
                'address' => '123 Main Street, Manila',
                'status' => 'active',
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@example.com',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'age' => 25,
                'sex' => 'female',
                'contact_number' => '09123456782',
                'address' => '456 Oak Avenue, Quezon City',
                'status' => 'active',
            ],
            [
                'name' => 'Alex Reyes',
                'email' => 'alex.reyes@example.com',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'age' => 30,
                'sex' => 'other',
                'contact_number' => '09123456783',
                'address' => '789 Pine Road, Makati',
                'status' => 'active',
            ]
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        // Create additional random users (optional)
        User::factory()->count(15)->create([
            'role' => 'user',
            'password' => Hash::make('Password123!'),
            'status' => 'active',
        ]);
    }
}
