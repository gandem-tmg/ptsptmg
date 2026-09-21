<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            // Seksi penanggung jawab default untuk layanan ini.
            // Nullable dulu supaya aman terhadap data layanan lama yang sudah ada,
            // lalu wajib diisi begitu proses pemetaan data selesai.
            $table->foreignId('seksi_id')->nullable()->after('id')
                ->constrained('seksi')->nullOnDelete();

            // Sekadar penanda tampilan (badge) di halaman publik.
            // Alur sistem (disposisi, update status) TETAP SAMA untuk ketiganya —
            // ini murni informasi bagi pemohon, bukan pencabang alur.
            $table->enum('tipe_pelaksanaan', ['full_digital', 'perlu_fisik', 'sistem_eksternal'])
                ->default('full_digital')
                ->after('kode_layanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('seksi_id');
            $table->dropColumn('tipe_pelaksanaan');
        });
    }
};
