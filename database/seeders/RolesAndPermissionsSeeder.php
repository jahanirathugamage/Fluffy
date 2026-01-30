<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        echo "=== Creating Permissions ===\n";

        // Create permissions
        $permissions = [
            'view-products' => 'View product listings',
            'buy-products' => 'Purchase products',
            'manage-products' => 'Create, edit, and delete products',
            'manage-orders' => 'View and manage customer orders',
            'manage-users' => 'Manage user accounts',
            'view-dashboard' => 'Access employee dashboard',
        ];

        foreach ($permissions as $name => $description) {
            $permission = Permission::firstOrCreate(
                ['name' => $name],
                ['guard_name' => 'web']
            );
            echo "✓ Created permission: {$name}\n";
        }

        echo "\n=== Creating/Updating Roles ===\n";

        // Create or get roles
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        echo "✓ Roles ready: customer, employee\n";

        echo "\n=== Syncing Permissions to Roles ===\n";

        // Sync permissions to Customer role
        $customerRole->syncPermissions([
            'view-products',
            'buy-products',
        ]);
        echo "✓ Customer permissions: view-products, buy-products\n";

        // Sync permissions to Employee role
        $employeeRole->syncPermissions([
            'view-products',
            'manage-orders',
            'view-dashboard',
        ]);
        echo "✓ Employee permissions: view-products, manage-orders, view-dashboard\n";

        echo "\n=== Assigning Roles to Users ===\n";

        // Ensure employee@fluffy.com has Employee role
        $employee = User::where('email', 'employee@fluffy.com')->first();
        if ($employee) {
            if (!$employee->hasRole('employee')) {
                $employee->assignRole('employee');
                echo "✓ Assigned Employee role to: employee@fluffy.com\n";
            } else {
                echo "✓ employee@fluffy.com already has Employee role\n";
            }
        } else {
            echo "⚠ employee@fluffy.com not found in database\n";
        }

        // Assign Customer role to users without any roles
        $usersWithoutRoles = User::doesntHave('roles')->get();
        $assignedCount = 0;
        
        foreach ($usersWithoutRoles as $user) {
            $user->assignRole('customer');
            $assignedCount++;
        }

        if ($assignedCount > 0) {
            echo "✓ Assigned Customer role to {$assignedCount} user(s) without roles\n";
        } else {
            echo "✓ All users already have roles assigned\n";
        }

        echo "\n=== Summary ===\n";
        echo "Permissions created: " . Permission::count() . "\n";
        echo "Roles: " . Role::count() . "\n";
        echo "Total users: " . User::count() . "\n";
        echo "Users with roles: " . User::has('roles')->count() . "\n";
        echo "\n✅ Roles and permissions setup complete!\n";
    }
}
