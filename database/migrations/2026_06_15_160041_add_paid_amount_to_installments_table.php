<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0)->after('installment_amount');
        });

        // Smart query to secure live data: set paid_amount to installment_amount for already paid installments
        \Illuminate\Support\Facades\DB::table('installments')
            ->where('status', 'paid')
            ->update([
                'paid_amount' => \Illuminate\Support\Facades\DB::raw('installment_amount')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
};
