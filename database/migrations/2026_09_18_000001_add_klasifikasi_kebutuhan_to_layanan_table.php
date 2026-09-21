<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lapisan klasifikasi BARU untuk navigasi HALAMAN PUBLIK, terpisah dari
     * `seksi_id` (yang tetap dipakai apa adanya untuk disposisi/alur kerja
     * internal admin & petugas — TIDAK disentuh oleh migration ini).
     *
     * Filosofinya: struktur organisasi (seksi) tetap utuh di belakang layar,
     * tapi pengguna publik menavigasi lewat "apa yang mereka butuhkan", bukan
     * "seksi mana yang menangani". Lihat config/klasifikasi_layanan.php untuk
     * daftar resmi nilai kategori & target_pengguna yang valid.
     */
    public function up(): void
    {
        // Dibuat idempotent (aman dijalankan ulang): kalau sebelumnya sempat
        // gagal di tengah jalan (mis. 1-2 kolom sudah kebentuk sebelum error),
        // proses berikutnya cuma menambah kolom yang BELUM ada, bukan error
        // "Duplicate column" di kolom yang sudah sempat berhasil ditambahkan.
        Schema::table('layanan', function (Blueprint $table) {
            if (!Schema::hasColumn('layanan', 'kategori')) {
                $table->string('kategori')->nullable()->after('seksi_id');
            }
            if (!Schema::hasColumn('layanan', 'subkategori')) {
                $table->string('subkategori')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('layanan', 'target_pengguna')) {
                $table->json('target_pengguna')->nullable()->after('subkategori');
            }
            if (!Schema::hasColumn('layanan', 'tag_pencarian')) {
                $table->text('tag_pencarian')->nullable()->after('target_pengguna');
            }
            if (!Schema::hasColumn('layanan', 'jenis_layanan')) {
                $table->string('jenis_layanan')->nullable()->after('tag_pencarian');
            }
        });
    }

    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $kolom = array_filter(
                ['kategori', 'subkategori', 'target_pengguna', 'tag_pencarian', 'jenis_layanan'],
                fn ($k) => Schema::hasColumn('layanan', $k)
            );
            if ($kolom) {
                $table->dropColumn($kolom);
            }
        });
    }
};
