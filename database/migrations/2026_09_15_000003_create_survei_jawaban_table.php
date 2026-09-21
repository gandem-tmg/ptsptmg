<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu baris = satu jawaban untuk satu pertanyaan dalam satu respon.
     * Cuma salah satu dari 3 kolom jawaban yang terisi, tergantung
     * survei_pertanyaan.tipe.
     */
    public function up(): void
    {
        Schema::create('survei_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survei_respon_id')->constrained('survei_respon')->cascadeOnDelete();
            $table->foreignId('survei_pertanyaan_id')->constrained('survei_pertanyaan')->cascadeOnDelete();
            $table->unsignedTinyInteger('nilai_rating')->nullable(); // 1-4, dipakai untuk tipe skala_4
            $table->string('pilihan_jawaban')->nullable(); // dipakai untuk tipe pilihan_ganda
            $table->text('jawaban_teks')->nullable(); // dipakai untuk tipe teks
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei_jawaban');
    }
};
