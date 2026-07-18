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
        Schema::create('logbook_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logbook_id')->constrained('logbooks')->onDelete('cascade');
            // $table->string('judul');
            $table->text('catatan');
            $table->date('tanggal_kegiatan');
            $table->string('tools');
            $table->foreignId('teknisi')->constrained('users')->onDelete('cascade');
            $table->datetime('mulai');
            $table->datetime('selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbook_items');
    }
};
