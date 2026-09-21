<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tambah status 'dibatalkan': dipakai saat PEMOHON membatalkan
     * permohonannya sendiri (bukan ditolak oleh petugas/seksi — itu tetap
     * 'ditolak' atau 'dikembalikan'). Sengaja dibedakan supaya statistik
     * "selesai/ditolak/dibatalkan" tidak campur aduk.
     */
    public function up(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->enum('status', [
                'diajukan', 'verifikasi', 'proses', 'selesai', 'ditolak',
                'verifikasi_ptsp',
                'dikembalikan',
                'didisposisikan',
                'diproses_seksi',
                'selesai_seksi',
                'verifikasi_akhir',
                'dibatalkan',
            ])->default('diajukan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->enum('status', [
                'diajukan', 'verifikasi', 'proses', 'selesai', 'ditolak',
                'verifikasi_ptsp',
                'dikembalikan',
                'didisposisikan',
                'diproses_seksi',
                'selesai_seksi',
                'verifikasi_akhir',
            ])->default('diajukan')->change();
        });
    }
};
