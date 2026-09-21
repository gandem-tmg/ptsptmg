<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * CATATAN: mengubah tipe enum kolom yang sudah ada (->change()) butuh
     * paket "doctrine/dbal". Kalau belum terpasang, jalankan dulu:
     *   composer require doctrine/dbal
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hanya terisi kalau role = petugas_seksi (petugas dari seksi tertentu).
            $table->foreignId('seksi_id')->nullable()->after('role')
                ->constrained('seksi')->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            // 'petugas' (lama) = petugas loket PTSP.
            // Tambahan: petugas_seksi (petugas di seksi terkait) dan
            // pimpinan (Kepala Kantor, akses monitoring read-only).
            $table->enum('role', ['admin', 'petugas', 'petugas_seksi', 'pemohon', 'pimpinan'])
                ->default('pemohon')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'petugas', 'pemohon'])->default('pemohon')->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('seksi_id');
        });
    }
};
