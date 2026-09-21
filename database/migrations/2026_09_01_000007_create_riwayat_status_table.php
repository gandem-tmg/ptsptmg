<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * PENTING: tabel ini didesain append-only. Jangan pernah menyediakan
     * fitur edit/hapus baris di sini dari sisi aplikasi — riwayat_status
     * adalah dasar penelusuran tanggung jawab (siapa update apa, kapan).
     * Sengaja tidak pakai updated_at karena baris tidak boleh diubah.
     */
    public function up(): void
    {
        Schema::create('riwayat_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained('permohonan')->cascadeOnDelete();
            $table->string('status');
            $table->text('catatan')->nullable();
            // Nullable: pengajuan guest (belum login) tidak punya user_id.
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_status');
    }
};
