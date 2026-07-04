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
        Schema::create('officer_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recovery_officer_id')->constrained('recovery_officers')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            // Ek customer ek officer ke under sirf ek dafa assign ho
            $table->unique(['recovery_officer_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_customers');
    }
};
