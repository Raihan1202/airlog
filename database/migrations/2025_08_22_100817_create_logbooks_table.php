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
        Schema::create('logbooks', function (Blueprint $table) {
			$table->id();
			$table->foreignId('unit_id')->constrained('units');
			$table->date('date');
			$table->string('judul');
			$table->enum('shift', ['1','2','3']);
			$table->foreignId('created_by')->constrained('users');
			$table->foreignId('approved_by')->nullable()->constrained('users');
			$table->boolean('is_approved')->default(false);
			$table->foreignId('signed_by')->nullable()->constrained('users');
			$table->timestamp('signed_at')->nullable();
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
