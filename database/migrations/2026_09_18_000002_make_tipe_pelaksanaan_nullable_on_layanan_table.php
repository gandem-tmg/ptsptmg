<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `tipe_pelaksanaan` tadinya wajib salah satu dari 3 nilai (default
     * 'full_digital'), jadi admin yang belum yakin cara pelaksanaan suatu
     * layanan terpaksa NEBAK — dan tebakan yang salah ditampilkan sebagai
     * badge di halaman publik, berpotensi menyesatkan pemohon.
     *
     * Diubah jadi nullable: kalau belum yakin, biarkan kosong -> badge-nya
     * otomatis TIDAK ditampilkan sama sekali di kartu/detail layanan
     * (lihat perubahan di layanan-mini-card.blade.php & _show-content.blade.php),
     * daripada nampilin badge yang belum tentu benar.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE layanan MODIFY tipe_pelaksanaan ENUM('full_digital','perlu_fisik','sistem_eksternal') NULL DEFAULT NULL");
        } else {
            // sqlite/pgsql/sqlsrv — Laravel + doctrine/dbal (sudah terpasang
            // di project ini) yang urus ALTER-nya.
            Schema::table('layanan', function (Blueprint $table) {
                $table->enum('tipe_pelaksanaan', ['full_digital', 'perlu_fisik', 'sistem_eksternal'])
                    ->nullable()->default(null)->change();
            });
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        // Isi dulu baris yang null biar gak gagal pas dipaksa NOT NULL lagi.
        DB::table('layanan')->whereNull('tipe_pelaksanaan')->update(['tipe_pelaksanaan' => 'full_digital']);

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE layanan MODIFY tipe_pelaksanaan ENUM('full_digital','perlu_fisik','sistem_eksternal') NOT NULL DEFAULT 'full_digital'");
        } else {
            Schema::table('layanan', function (Blueprint $table) {
                $table->enum('tipe_pelaksanaan', ['full_digital', 'perlu_fisik', 'sistem_eksternal'])
                    ->default('full_digital')->nullable(false)->change();
            });
        }
    }
};
