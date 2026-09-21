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
        Schema::table('permohonan', function (Blueprint $table) {
            // Null = masih di tangan PTSP (belum/sudah tidak lagi di seksi manapun).
            $table->foreignId('current_seksi_id')->nullable()->after('layanan_id')
                ->constrained('seksi')->nullOnDelete();

            $table->string('qr_code_path')->nullable()->after('no_tiket');

            // online = pemohon ajukan sendiri lewat web; walk_in = diinput petugas loket.
            $table->enum('sumber_pengajuan', ['online', 'walk_in'])
                ->default('online')
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_seksi_id');
            $table->dropColumn(['qr_code_path', 'sumber_pengajuan']);
        });
    }
};
