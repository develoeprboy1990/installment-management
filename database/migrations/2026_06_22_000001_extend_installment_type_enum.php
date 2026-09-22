<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL enum modification requires raw statement
        DB::statement("ALTER TABLE purchases MODIFY COLUMN installment_type ENUM('daily','weekly','monthly','3months','6months','1year') DEFAULT 'monthly'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE purchases MODIFY COLUMN installment_type ENUM('daily','weekly','monthly') DEFAULT 'monthly'");
    }
};
