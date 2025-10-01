<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('activities', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id')->nullable();
			$table->string('action');
			$table->string('model_type');
			$table->unsignedBigInteger('model_id')->nullable();
			$table->text('message');
			$table->json('changes')->nullable();
			$table->boolean('is_read')->default(false);
			$table->timestamps();

			$table->index(['model_type', 'model_id']);
			$table->index(['user_id', 'is_read']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('activities');
	}
};


