<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Dukung permohonan offline untuk layanan yang TIDAK ADA di katalog
     * `layanan` — kadang pemohon datang dengan permintaan yang masih tupoksi
     * Kemenag tapi belum/tidak didaftarkan sebagai layanan resmi. Daripada
     * menolak atau memaksa dicatat di layanan yang tidak sesuai, petugas
     * PTSP bisa mengisi manual (nama & deskripsi layanan sendiri) supaya
     * permohonan tetap tercatat & bisa dilacak seperti biasa.
     *
     * `layanan_id` sengaja dibuat nullable (khusus untuk kasus manual ini,
     * permohonan lewat katalog resmi tetap selalu mengisi layanan_id seperti
     * sebelumnya). `manual_seksi_id` menyimpan seksi yang dipilih petugas
     * di awal (dipakai sebagai default tujuan disposisi, karena tidak ada
     * layanan->seksi_id untuk dirujuk).
     */
    public function up(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->foreignId('layanan_id')->nullable()->change();

            $table->boolean('is_manual')->default(false)->after('layanan_id');
            $table->foreignId('manual_seksi_id')->nullable()->after('is_manual')
                ->constrained('seksi')->nullOnDelete();
            $table->string('nama_layanan_manual')->nullable()->after('manual_seksi_id');
            $table->text('deskripsi_layanan_manual')->nullable()->after('nama_layanan_manual');
            // Untuk permohonan manual, kelengkapan berkas cukup dicatat lengkap/
            // belum lengkap secara keseluruhan (bukan checklist per persyaratan,
            // karena memang tidak ada daftar persyaratan resmi untuk layanan ini).
            $table->enum('kelengkapan_manual', ['lengkap', 'belum_lengkap'])->nullable()->after('deskripsi_layanan_manual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('manual_seksi_id');
            $table->dropColumn(['is_manual', 'nama_layanan_manual', 'deskripsi_layanan_manual', 'kelengkapan_manual']);
            $table->foreignId('layanan_id')->nullable(false)->change();
        });
    }
};
