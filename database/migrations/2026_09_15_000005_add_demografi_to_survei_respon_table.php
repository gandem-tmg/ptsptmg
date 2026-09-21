<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Field demografi ringan mengikuti unsur profil responden standar SKM
     * Permenpan-RB No. 14/2017 (jenis kelamin, usia, pendidikan, pekerjaan)
     * — dipakai untuk breakdown IKM, TAPI dibuat nullable & cuma dipakai di
     * survei UMUM (publik). Survei per-layanan (pemohon login) sengaja
     * tidak dibebani field ini karena identitasnya sudah jelas dari akun.
     * Semua tetap opsional (nullable) supaya tidak menambah friksi berarti
     * buat pengisi survei.
     */
    public function up(): void
    {
        Schema::table('survei_respon', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('nama_pengisi');
            $table->string('usia_rentang', 20)->nullable()->after('jenis_kelamin');
            $table->string('pendidikan_terakhir', 50)->nullable()->after('usia_rentang');
            $table->string('pekerjaan_utama', 50)->nullable()->after('pendidikan_terakhir');
        });
    }

    public function down(): void
    {
        Schema::table('survei_respon', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'usia_rentang', 'pendidikan_terakhir', 'pekerjaan_utama']);
        });
    }
};
