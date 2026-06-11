<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * MigrateToMultiTenantSeeder
 *
 * Run this ONCE after running the new migrations.
 * It will:
 *  1. Create one Tenant for your original/existing store.
 *  2. Assign ALL existing records in every table to that tenant.
 *  3. Create a SuperAdmin user (no tenant_id) to manage all stores.
 *
 * Run: php artisan db:seed --class=MigrateToMultiTenantSeeder
 */
class MigrateToMultiTenantSeeder extends Seeder
{
    public function run(): void
    {
        // ── Step 1: Create the first (original) tenant ────────────────────────
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'main-store'],
            [
                'name'   => 'Main Store',   // ← apna store name yahan likhain
                'slug'   => 'main-store',
                'email'  => 'mainstore@example.com',
                'status' => 'active',
            ]
        );

        $this->command->info("✅ Tenant created: [{$tenant->id}] {$tenant->name}");

        // ── Step 2: Assign all existing rows to this tenant ──────────────────
        $tables = [
            'customers',
            'products',
            'guarantors',
            'recovery_officers',
            'purchases',
            'installments',
            'expenses',
            'activities',
            'settings',
            'users',   // existing users become part of main store
        ];

        foreach ($tables as $table) {
            $updated = DB::table($table)
                         ->whereNull('tenant_id')
                         ->update(['tenant_id' => $tenant->id]);

            $this->command->info("   → {$table}: {$updated} records assigned to Tenant #{$tenant->id}");
        }

        // ── Step 3: Create a SuperAdmin (no tenant_id) ────────────────────────
        $superAdminEmail = 'superadmin@example.com';

        if (!User::where('email', $superAdminEmail)->exists()) {
            $superAdmin = User::create([
                'tenant_id' => null,   // ← NULL means SuperAdmin (no store)
                'name'      => 'Super Admin',
                'email'     => $superAdminEmail,
                'password'  => bcrypt('superadmin123'),  // ← change this after seeding!
            ]);

            // Assign SuperAdmin role (create it if it doesn't exist)
            $superAdmin->assignRole(
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web'])
            );

            $this->command->info("✅ SuperAdmin created: {$superAdminEmail} / password: superadmin123");
            $this->command->warn("   ⚠️  Please change the SuperAdmin password immediately after login!");
        } else {
            $this->command->info("   SuperAdmin already exists, skipping.");
        }

        $this->command->info('');
        $this->command->info('🎉 Multi-tenant migration complete!');
        $this->command->info("   Store Admin login: (your existing users)");
        $this->command->info("   SuperAdmin login: {$superAdminEmail}");
        $this->command->info("   SuperAdmin panel: /superadmin/dashboard");
    }
}
