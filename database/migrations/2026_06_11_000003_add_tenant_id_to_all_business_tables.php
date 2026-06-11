<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * All business tables that need tenant_id scoping.
     * recovery_officers: employee_id has unique constraint — we'll handle it separately.
     */
    protected array $simpleTables = [
        'customers',
        'products',
        'guarantors',
        'purchases',
        'installments',
        'expenses',
        'activities',
        'settings',
    ];

    public function up(): void
    {
        // Add tenant_id to all simple business tables
        foreach ($this->simpleTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->foreignId('tenant_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('tenants')
                      ->onDelete('cascade');
                $table->index('tenant_id');
            });
        }

        // Recovery officers — employee_id was unique globally,
        // now it should be unique per tenant (drop old unique, add composite unique)
        Schema::table('recovery_officers', function (Blueprint $table) {
            $table->foreignId('tenant_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('tenants')
                  ->onDelete('cascade');
            $table->index('tenant_id');
            // Drop old global unique on employee_id
            $table->dropUnique(['employee_id']);
            // New composite unique: employee_id must be unique per tenant
            $table->unique(['tenant_id', 'employee_id']);
        });

        // Customers — account_no & nic were globally unique,
        // now they should be unique per tenant
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['account_no']);
            $table->dropUnique(['nic']);
            $table->unique(['tenant_id', 'account_no']);
            $table->unique(['tenant_id', 'nic']);
        });
    }

    public function down(): void
    {
        // Reverse composite uniques on customers
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'account_no']);
            $table->dropUnique(['tenant_id', 'nic']);
            $table->dropForeign(['tenant_id']);
            $table->dropIndex(['tenant_id']);
            $table->dropColumn('tenant_id');
            $table->unique('account_no');
            $table->unique('nic');
        });

        // Reverse recovery_officers
        Schema::table('recovery_officers', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'employee_id']);
            $table->dropForeign(['tenant_id']);
            $table->dropIndex(['tenant_id']);
            $table->dropColumn('tenant_id');
            $table->unique('employee_id');
        });

        // Reverse all simple tables
        foreach (array_reverse($this->simpleTables) as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
