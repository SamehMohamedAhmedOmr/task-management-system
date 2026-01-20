<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $managerRole = Role::where('key', Role::MANAGER)->first();
        $userRole = Role::where('key', Role::USER)->first();

        // Create Managers
        User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager One',
                'password' => Hash::make('password'),
                'role_id' => $managerRole->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $managerRole->id,
            ]
        );

        // Create Users
        for ($i = 1; $i <= 5; $i++) {
            User::firstOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name' => "User {$i}",
                    'password' => Hash::make('password'),
                    'role_id' => $userRole->id,
                ]
            );
        }
    }
}
