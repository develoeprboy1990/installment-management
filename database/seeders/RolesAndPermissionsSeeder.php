<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions with CRUD operations for each module
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // Customers Module
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            'view-customer-statement',
            
            // Products Module
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            
            // Purchases Module
            'view-purchases',
            'create-purchases',
            'edit-purchases',
            'delete-purchases',
            'process-payment',
            
            // Installments Module
            'view-installments',
            'create-installments',
            'edit-installments',
            'delete-installments',
            'update-installment-status',
            'print-receipt',
            
            // Guarantors Module
            'view-guarantors',
            'create-guarantors',
            'edit-guarantors',
            'delete-guarantors',
            
            // Recovery Officers Module
            'view-recovery-officers',
            'create-recovery-officers',
            'edit-recovery-officers',
            'delete-recovery-officers',
            
            // Expenses Module
            'view-expenses',
            'create-expenses',
            'edit-expenses',
            'delete-expenses',
            
            // Reports Module
            'view-reports',
            'export-reports',
            
            // Settings Module
            'view-settings',
            'edit-settings',
            
            // Users Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'assign-roles',
            'manage-permissions',
            
            // Activities/Logs
            'view-activities',
            
            // Profile
            'view-profile',
            'edit-profile',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles and assign permissions
        
        // Admin Role - Full Access
        $adminPermissions = [
            'view-dashboard',
            // Customers
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            'view-customer-statement',
            // Products
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            // Purchases
            'view-purchases',
            'create-purchases',
            'edit-purchases',
            'delete-purchases',
            'process-payment',
            // Installments
            'view-installments',
            'create-installments',
            'edit-installments',
            'delete-installments',
            'update-installment-status',
            'print-receipt',
            // Guarantors
            'view-guarantors',
            'create-guarantors',
            'edit-guarantors',
            'delete-guarantors',
            // Recovery Officers
            'view-recovery-officers',
            'create-recovery-officers',
            'edit-recovery-officers',
            'delete-recovery-officers',
            // Expenses
            'view-expenses',
            'create-expenses',
            'edit-expenses',
            'delete-expenses',
            // Reports
            'view-reports',
            'export-reports',
            // Settings
            'view-settings',
            'edit-settings',
            // Users
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'assign-roles',
            'manage-permissions',
            // Activities
            'view-activities',
            // Profile
            'view-profile',
            'edit-profile',
        ];

        // User Role - Limited Access (Read-only mostly)
        $userPermissions = [
            'view-dashboard',
            // Customers - View only
            'view-customers',
            'view-customer-statement',
            // Products - View only
            'view-products',
            // Purchases - View only
            'view-purchases',
            // Installments - View and print
            'view-installments',
            'print-receipt',
            // Guarantors - View only
            'view-guarantors',
            // Recovery Officers - View only
            'view-recovery-officers',
            // Expenses - Full access
            'view-expenses',
            'create-expenses',
            'edit-expenses',
            'delete-expenses',
            // Reports - View only
            'view-reports',
            // Profile
            'view-profile',
            'edit-profile',
        ];

        // Create Admin role and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions($adminPermissions);

        // Create User role and assign permissions
        $userRole = Role::firstOrCreate(['name' => 'User']);
        $userRole->syncPermissions($userPermissions);

        // Create Customer role (if needed for customer portal)
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);
        $customerRole->syncPermissions([
            'view-dashboard',
            'view-profile',
            'edit-profile',
        ]);

        echo "✅ Roles and Permissions seeded successfully!\n";
        echo "   - Admin role: " . count($adminPermissions) . " permissions\n";
        echo "   - User role: " . count($userPermissions) . " permissions\n";
        echo "   - Customer role: 3 permissions\n";
    }
}