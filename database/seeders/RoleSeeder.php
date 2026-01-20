<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(
            ['key' => Role::MANAGER],
            ['name' => 'Manager']
        );
        Role::firstOrCreate(
            ['key' => Role::USER],
            ['name' => 'User']
        );
    }
}
