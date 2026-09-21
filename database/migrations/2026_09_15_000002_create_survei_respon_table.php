<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu baris = satu kali pengisian survei (per_layanan atau umum).
     * Identitas responden SENGAJA diminimalkan — cuma nama, sekadar cukup
     * untuk akuntabilitas, bukan pendataan lengkap.
     */
    public function up(): void
    {
        Schema::create('survei_respon', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_survei', ['per_layanan', 'umum']);
            $table->foreignId('permohonan_id')->nullable()->constrained('permohonan')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_pengisi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei_respon');
    }
};
