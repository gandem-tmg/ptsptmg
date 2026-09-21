<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * CATATAN: butuh "doctrine/dbal" (sama seperti migration users di atas).
     * Status lama tetap dipertahankan sebagai bagian dari enum baru supaya
     * data existing tidak invalid; kalau perlu mapping ulang data lama,
     * lakukan lewat data migration terpisah sebelum dipakai di production.
     */
    public function up(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->enum('status', [
                // status lama (dipertahankan untuk kompatibilitas data existing)
                'diajukan', 'verifikasi', 'proses', 'selesai', 'ditolak',
                // status baru sesuai alur disposisi
                'verifikasi_ptsp',
                'dikembalikan',
                'didisposisikan',
                'diproses_seksi',
                'selesai_seksi',
                'verifikasi_akhir',
            ])->default('diajukan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->enum('status', ['diajukan', 'verifikasi', 'proses', 'selesai', 'ditolak'])
                ->default('diajukan')->change();
        });
    }
};
