<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tidak semua dari 45 layanan menghasilkan dokumen/surat sebagai
     * output akhir (misal: konsultasi, bimbingan, verifikasi langsung
     * bisa selesai begitu pemohon dilayani, tanpa perlu file diunggah).
     * Kolom ini dipakai admin untuk menandai mana yang butuh dokumen
     * resmi vs mana yang cukup ditandai selesai langsung oleh seksi.
     */
    public function up(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->boolean('perlu_dokumen_hasil')->default(true)->after('tipe_pelaksanaan');
        });
    }

    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn('perlu_dokumen_hasil');
        });
    }
};
