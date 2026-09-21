<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom `nik` di tabel `permohonan` sebelumnya diberi UNIQUE global
     * (lihat migration add_guest_fields_to_permohonan_table). Efeknya:
     * satu NIK yang sama HANYA BISA dipakai untuk satu permohonan guest
     * SEUMUR HIDUP — begitu satu warga mengajukan satu layanan tanpa akun,
     * NIK-nya "terpakai" dan tidak bisa dipakai lagi untuk mengajukan
     * layanan lain di kemudian hari. Ini bukan perilaku yang diinginkan:
     * satu NIK realistis akan punya banyak permohonan berbeda sepanjang
     * waktu. Constraint ini dihapus di sini.
     */
    public function up(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->dropUnique('permohonan_nik_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->unique('nik');
        });
    }
};
