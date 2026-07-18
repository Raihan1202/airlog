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
        Schema::create('recent_devices', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->onDelete('cascade');
			$table->string('ip_address')->nullable();
			$table->text('user_agent')->nullable();
			$table->string('device_type')->nullable(); // e.g., desktop, phone
			$table->string('os')->nullable(); // e.g., Windows, Android
			$table->string('browser')->nullable(); // e.g., Chrome, Firefox
			$table->string('country')->nullable();
			$table->timestamp('last_login');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recent_devices');
    }
};
