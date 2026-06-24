<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('recovery_officer_id')->nullable()->constrained('recovery_officers')->onDelete('set null');
            $table->string('receipt_no')->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable(); // cash, bank, cheque
            $table->enum('payment_type', ['full', 'partial'])->default('full');
            $table->date('payment_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps(); // created_at will store exact datetime of transaction
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
