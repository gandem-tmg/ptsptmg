<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * CATATAN (sesi lanjutan, 28 Agt 2026):
 * Dump `db_untuk_ptsptmg-main.sql` dicek ulang — kolom `no_tiket` pada tabel
 * `permohonan` TERNYATA SUDAH punya UNIQUE KEY `permohonan_no_tiket_unique`
 * bawaan aplikasi asli (lihat baris ALTER TABLE di dump). Artinya safety-net
 * yang disebut di HANDOFF_NOTES.md poin 2d **sudah otomatis terpasang**,
 * TIDAK perlu index tambahan untuk `no_tiket` di migration ini (index lama
 * di versi sebelumnya dihapus supaya tidak redundan).
 *
 * Yang masih perlu ditambahkan justru safety-net untuk PASANGAN
 * (`sumber`, `id_layanan_asal`) agar pengecekan idempotensi Fase 1 di
 * WebhookController@receiveTiket (cek dulu baru insert) punya jaminan di
 * level DB juga, bukan cuma di level aplikasi — makanya ditambahkan unique
 * index komposit di bawah. NULL pada id_layanan_asal (permohonan internal,
 * sumber='internal') tidak masalah karena MySQL unique index mengizinkan
 * banyak NULL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            if (!Schema::hasColumn('permohonan', 'sumber')) {
                $table->string('sumber')->default('internal')->after('layanan_id')
                    ->comment("Asal permohonan: 'internal' (form PTSP) atau 'layanan_madrasah' (webhook)");
            }
            if (!Schema::hasColumn('permohonan', 'id_layanan_asal')) {
                $table->string('id_layanan_asal')->nullable()->after('sumber')
                    ->comment('id_layanan pada aplikasi Layanan Madrasah (sumber pengirim), BUKAN FK ke tabel layanan lokal');
            }
            if (!Schema::hasColumn('permohonan', 'nsm')) {
                $table->string('nsm', 20)->nullable()->after('id_layanan_asal');
            }
            if (!Schema::hasColumn('permohonan', 'nama_madrasah')) {
                $table->string('nama_madrasah')->nullable()->after('nsm');
            }
            if (!Schema::hasColumn('permohonan', 'link_drive_berkas')) {
                $table->string('link_drive_berkas')->nullable()->after('nama_madrasah')
                    ->comment('Link Google Drive berkas dari Layanan Madrasah (Fase 1)');
            }
            if (!Schema::hasColumn('permohonan', 'link_drive_rekom')) {
                $table->string('link_drive_rekom')->nullable()->after('link_drive_berkas')
                    ->comment('Link Google Drive surat rekomendasi terbit (Fase 2)');
            }

            // `no_tiket` TIDAK diindex lagi di sini — sudah ada UNIQUE KEY
            // `permohonan_no_tiket_unique` bawaan tabel asli.
        });

        $driver = DB::connection()->getDriverName();
        $indexes = $driver === 'sqlite'
            ? collect(DB::select("PRAGMA index_list('permohonan')"))->pluck('name')->all()
            : collect(DB::select("SHOW INDEX FROM `permohonan`"))->pluck('Key_name')->all();

        // Unique index komposit terpisah (butuh nama eksplisit karena dua
        // kolom) — safety net idempotensi Fase 1 di level DB.
        if (!in_array('permohonan_sumber_id_layanan_asal_unique', $indexes, true)) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->unique(['sumber', 'id_layanan_asal'], 'permohonan_sumber_id_layanan_asal_unique');
            });
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        $indexes = $driver === 'sqlite'
            ? collect(DB::select("PRAGMA index_list('permohonan')"))->pluck('name')->all()
            : collect(DB::select("SHOW INDEX FROM `permohonan`"))->pluck('Key_name')->all();

        if (in_array('permohonan_sumber_id_layanan_asal_unique', $indexes, true)) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->dropUnique('permohonan_sumber_id_layanan_asal_unique');
            });
        }

        Schema::table('permohonan', function (Blueprint $table) {
            $table->dropColumn([
                'sumber', 'id_layanan_asal', 'nsm', 'nama_madrasah',
                'link_drive_berkas', 'link_drive_rekom',
            ]);
        });
    }
};
