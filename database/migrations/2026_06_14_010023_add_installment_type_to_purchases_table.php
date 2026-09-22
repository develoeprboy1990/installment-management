<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->enum('installment_type', ['daily', 'weekly', 'monthly'])
                  ->default('monthly')
                  ->after('installment_months');

            $table->unsignedInteger('installment_count')
                  ->nullable()
                  ->after('installment_type')
                  ->comment('Total installment count. Used for daily/weekly; monthly uses installment_months.');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['installment_type', 'installment_count']);
        });
    }
};
