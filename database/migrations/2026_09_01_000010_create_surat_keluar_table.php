<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained('permohonan')->cascadeOnDelete();
            $table->foreignId('template_surat_id')->nullable()->constrained('template_surat')->nullOnDelete();

            $table->string('nomor_surat')->unique();

            // Snapshot isi surat pada saat digenerate — supaya kalau template
            // di-edit belakangan, surat yang sudah terbit tidak ikut berubah.
            $table->text('isi_surat');

            $table->string('file_path');

            $table->enum('metode_ttd', ['belum_ttd', 'manual', 'tte'])->default('belum_ttd');
            $table->foreignId('ditandatangani_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_ttd')->nullable();

            // Kode acak (bukan ID sekuensial) dipakai di URL verifikasi publik,
            // supaya nomor surat tidak bisa ditebak/di-enumerasi orang lain.
            $table->string('kode_verifikasi', 40)->unique();
            $table->string('qr_verifikasi_path')->nullable();

            $table->foreignId('dibuat_oleh')->constrained('users')->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
