<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        echo "✓ Roles created: customer, employee\n";

        // Migrate existing users from the 'role' column to Spatie roles
        $users = User::all();
        $migratedCount = 0;

        foreach ($users as $user) {
            // Skip if user already has roles assigned via Spatie
            if ($user->roles()->count() > 0) {
                continue;
            }

            // Check the old 'role' column
            if (isset($user->role)) {
                $roleName = strtolower(trim($user->role));
                
                if ($roleName === 'customer') {
                    $user->assignRole('customer');
                    $migratedCount++;
                } elseif ($roleName === 'employee') {
                    $user->assignRole('employee');
                    $migratedCount++;
                } else {
                    // Default to customer if role is unknown
                    $user->assignRole('customer');
                    $migratedCount++;
                }
            } else {
                // If no role column, assign customer as default
                $user->assignRole('customer');
                $migratedCount++;
            }
        }

        echo "✓ Migrated {$migratedCount} user(s) to Spatie roles\n";
        echo "✓ Total users: " . $users->count() . "\n";
    }
}
