<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained('layanan')->cascadeOnDelete();
            $table->string('judul_template');

            // Isi surat pakai placeholder sederhana: {{nama_pemohon}}, {{nik}},
            // {{alamat_pemohon}}, {{no_tiket}}, {{nama_layanan}}, {{nomor_surat}},
            // {{tanggal_surat}}. Diganti via str_replace (BUKAN Blade::render),
            // supaya template tidak bisa menjalankan kode PHP sembarangan.
            $table->text('isi_template');

            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_surat');
    }
};
