<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 4 poin tambahan Standar Pelayanan (selain Persyaratan yang sudah
     * ada duluan di tabel persyaratan) — mengikuti komponen wajib Standar
     * Pelayanan sesuai UU No. 25/2009 & Permenpan RB No. 15/2014.
     * Semua nullable/text bebas format (bisa poin bernomor, paragraf, dst)
     * supaya admin fleksibel mengisi sesuai SK Standar Pelayanan yang
     * sudah ada per layanan, bukan dipaksa ke struktur baku.
     */
    public function up(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->text('sistem_mekanisme_prosedur')->nullable()->after('perlu_dokumen_hasil');
            $table->text('jangka_waktu_pelayanan')->nullable()->after('sistem_mekanisme_prosedur');
            $table->text('biaya_tarif')->nullable()->after('jangka_waktu_pelayanan');
            $table->text('produk_pelayanan')->nullable()->after('biaya_tarif');
        });
    }

    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn(['sistem_mekanisme_prosedur', 'jangka_waktu_pelayanan', 'biaya_tarif', 'produk_pelayanan']);
        });
    }
};
