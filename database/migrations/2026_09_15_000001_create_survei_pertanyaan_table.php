<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Daftar pertanyaan survei — dikelola dinamis oleh admin (tambah/ubah/
     * nonaktifkan), bukan hardcode di kode. Begitu aturan/indikator resmi
     * yang mau dipakai sudah dikoordinasikan, admin tinggal sesuaikan di
     * sini tanpa perlu ubah kode.
     *
     * tipe 'skala_4': jawaban 1-4 (A-D) sesuai skala resmi Permenpan RB
     * No. 14/2017 tentang SKM (BUKAN 1-5) — opsi_jawaban menyimpan 4 label
     * teks custom per pertanyaan (mis. "Tidak Sesuai".."Sangat Sesuai"),
     * tapi nilainya selalu diskors 1-4 di belakang layar.
     * tipe 'pilihan_ganda': pilihan bebas, tidak diskor (mis. data demografis).
     * tipe 'teks': jawaban esai/komentar bebas.
     */
    public function up(): void
    {
        Schema::create('survei_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->string('teks_pertanyaan');
            $table->enum('tipe', ['skala_4', 'pilihan_ganda', 'teks'])->default('skala_4');
            $table->json('opsi_jawaban')->nullable();
            $table->enum('jenis_survei', ['per_layanan', 'umum', 'keduanya'])->default('keduanya');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei_pertanyaan');
    }
};
