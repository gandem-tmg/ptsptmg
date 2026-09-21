<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sebagian persyaratan hasil ekstraksi dari dokumen Standar Pelayanan
     * berupa kalimat panjang (>255 karakter), jadi VARCHAR bawaan kolom ini
     * tidak cukup. Ganti ke TEXT supaya tidak ada lagi yang kepotong/gagal insert.
     */
    public function up(): void
    {
        Schema::table('persyaratan', function (Blueprint $table) {
            $table->text('nama_persyaratan')->change();
        });
    }

    public function down(): void
    {
        Schema::table('persyaratan', function (Blueprint $table) {
            $table->string('nama_persyaratan')->change();
        });
    }
};
