<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Core lookup tables first
        $this->call([
            AnimalSeeder::class,
            CategorySeeder::class,
        ]);

        // Seed demo users for marking/demo (RBAC evidence)
        User::firstOrCreate(
            ['email' => 'admin@fluffy.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'employee@fluffy.com'],
            [
                'name' => 'Employee',
                'password' => Hash::make('Employee@12345'),
                'role' => 'employee',
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@fluffy.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('Customer@12345'),
                'role' => 'customer',
            ]
        );

        // Seed roles, permissions, and migrate users to Spatie
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
