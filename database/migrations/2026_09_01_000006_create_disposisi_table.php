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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained('permohonan')->cascadeOnDelete();
            $table->foreignId('seksi_id')->constrained('seksi')->restrictOnDelete();

            // Petugas PTSP yang mendisposisikan.
            $table->foreignId('didisposisikan_oleh')->constrained('users')->restrictOnDelete();
            // Petugas seksi yang meng-acknowledge (nullable: belum tentu langsung diterima).
            $table->foreignId('diterima_oleh')->nullable()->constrained('users')->nullOnDelete();

            $table->text('catatan')->nullable();

            $table->timestamp('tanggal_disposisi');
            $table->timestamp('tanggal_diterima')->nullable();
            $table->timestamp('tanggal_selesai_seksi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
