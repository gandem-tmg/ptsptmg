<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * PERBAIKAN BUG KRITIS (sesi lanjutan, 28 Agt 2026):
 *
 * Migration 2026_08_28_000001 menambahkan unique index komposit
 * (`sumber`, `id_layanan_asal`) sebagai safety-net idempotensi Fase 1.
 * Tapi `id_layanan_asal` HANYA berisi id lokal dari SATU tabel di
 * Layanan Madrasah (mis. layanan_rekomendasis.id = 5), TIDAK menyertakan
 * tipe layanan asalnya. Karena integrasi sudah di-generalize ke 6 tipe
 * layanan (rekomendasi, rekomendasi_kamad, pindah_sekolah, input_siswa,
 * reaktivasi, reset_verval) yang MASING-MASING punya auto-increment id
 * sendiri mulai dari 1, maka:
 *
 *   - layanan_rekomendasis id=5    -> kirim id_layanan=5
 *   - layanan_input_siswa  id=5    -> kirim id_layanan=5 (TABRAKAN!)
 *
 * Sebelum migration ini, WebhookController@receiveTiket akan salah
 * mengenali permohonan tipe kedua sebagai "sudah pernah dikirim
 * sebelumnya" (karena idempotency check hanya berdasar id_layanan_asal +
 * sumber='layanan_madrasah'), lalu MENGEMBALIKAN no_tiket MILIK PENGAJUAN
 * TIPE LAIN yang sama sekali tidak berhubungan. Ini bug data-integrity
 * yang serius (tiket ketuker antar tipe layanan berbeda).
 *
 * Perbaikan: tambah kolom `tipe_layanan_asal` (diisi dari payload webhook
 * baru `tipe_layanan` yang dikirim RequestTiketPtspJob — lihat perubahan
 * di WebhookController), lalu ganti unique index supaya mencakup kolom
 * ini juga.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            if (!Schema::hasColumn('permohonan', 'tipe_layanan_asal')) {
                $table->string('tipe_layanan_asal')->nullable()->after('id_layanan_asal')
                    ->comment("Tipe layanan asal di aplikasi Layanan Madrasah: rekomendasi, rekomendasi_kamad, pindah_sekolah, input_siswa, reaktivasi, atau reset_verval");
            }
        });

        $driver = DB::connection()->getDriverName();
        $indexes = $driver === 'sqlite'
            ? collect(DB::select("PRAGMA index_list('permohonan')"))->pluck('name')->all()
            : collect(DB::select("SHOW INDEX FROM `permohonan`"))->pluck('Key_name')->all();

        // Ganti unique index lama (sumber, id_layanan_asal) yang rawan
        // tabrakan antar tipe, dengan versi yang menyertakan tipe_layanan_asal.
        if (in_array('permohonan_sumber_id_layanan_asal_unique', $indexes, true)) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->dropUnique('permohonan_sumber_id_layanan_asal_unique');
            });
        }

        if (!in_array('permohonan_sumber_tipe_id_layanan_asal_unique', $indexes, true)) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->unique(
                    ['sumber', 'tipe_layanan_asal', 'id_layanan_asal'],
                    'permohonan_sumber_tipe_id_layanan_asal_unique'
                );
            });
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        $indexes = $driver === 'sqlite'
            ? collect(DB::select("PRAGMA index_list('permohonan')"))->pluck('name')->all()
            : collect(DB::select("SHOW INDEX FROM `permohonan`"))->pluck('Key_name')->all();

        if (in_array('permohonan_sumber_tipe_id_layanan_asal_unique', $indexes, true)) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->dropUnique('permohonan_sumber_tipe_id_layanan_asal_unique');
            });
        }

        if (!in_array('permohonan_sumber_id_layanan_asal_unique', $indexes, true)) {
            Schema::table('permohonan', function (Blueprint $table) {
                $table->unique(['sumber', 'id_layanan_asal'], 'permohonan_sumber_id_layanan_asal_unique');
            });
        }

        Schema::table('permohonan', function (Blueprint $table) {
            $table->dropColumn('tipe_layanan_asal');
        });
    }
};
