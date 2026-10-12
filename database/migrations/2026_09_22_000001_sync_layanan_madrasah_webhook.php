<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Sinkronisasi integrasi Webhook Layanan Madrasah.
 *
 * Dibuat idempotent agar aman pada hosting yang:
 * - belum pernah memasang kolom webhook; atau
 * - sudah pernah menjalankan migration webhook versi lama.
 *
 * Tiket TIDAK diubah di sini. Kolom no_tiket/unique index bawaan PTSP tetap
 * dipakai oleh Permohonan::generateNoTiket().
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('permohonan', 'sumber')) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->string('sumber')->default('internal')->after('layanan_id')
                    ->comment("Asal permohonan: 'internal' atau 'layanan_madrasah'");
            });
        }

        if (!Schema::hasColumn('permohonan', 'id_layanan_asal')) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->string('id_layanan_asal')->nullable()->after('sumber');
            });
        }

        if (!Schema::hasColumn('permohonan', 'tipe_layanan_asal')) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->string('tipe_layanan_asal')->nullable()->after('id_layanan_asal');
            });
        }

        if (!Schema::hasColumn('permohonan', 'nsm')) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->string('nsm', 20)->nullable()->after('tipe_layanan_asal');
            });
        }

        if (!Schema::hasColumn('permohonan', 'nama_madrasah')) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->string('nama_madrasah')->nullable()->after('nsm');
            });
        }

        if (!Schema::hasColumn('permohonan', 'link_drive_berkas')) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->string('link_drive_berkas')->nullable()->after('nama_madrasah');
            });
        }

        if (!Schema::hasColumn('permohonan', 'link_drive_rekom')) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->string('link_drive_rekom')->nullable()->after('link_drive_berkas');
            });
        }

        // Migrasi index lama (sumber,id_layanan_asal) -> index baru
        // (sumber,tipe_layanan_asal,id_layanan_asal). Aman terhadap hosting
        // yang sudah pernah memasang migration webhook versi sebelumnya.
        $driver = DB::connection()->getDriverName();
        $indexes = $driver === 'sqlite'
            ? collect(DB::select("PRAGMA index_list('permohonan')"))->pluck('name')->all()
            : collect(DB::select("SHOW INDEX FROM `permohonan`"))->pluck('Key_name')->all();

        $newIndex = 'permohonan_sumber_tipe_id_layanan_asal_unique';
        $oldIndex = 'permohonan_sumber_id_layanan_asal_unique';

        if (in_array($oldIndex, $indexes, true)) {
            Schema::table('permohonan', function (Blueprint $table) use ($oldIndex) {
                $table->dropUnique($oldIndex);
            });
        }

        if (!in_array($newIndex, $indexes, true)) {
            Schema::table('permohonan', function (Blueprint $table) use ($newIndex) {
                $table->unique(
                    ['sumber', 'tipe_layanan_asal', 'id_layanan_asal'],
                    $newIndex
                );
            });
        }
    }

    public function down(): void
    {
        // Sengaja no-op: migration ini juga berfungsi sebagai sinkronisasi
        // untuk instalasi yang mungkin sudah memiliki kolom/index webhook
        // dari migration versi sebelumnya.
    }
};
