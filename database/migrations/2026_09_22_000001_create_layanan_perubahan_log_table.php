<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Log riwayat perubahan layanan oleh petugas seksi (fitur "kelola layanan
 * seksi sendiri"). Admin dan petugas seksi tetap bisa melihat siapa
 * mengubah apa dan kapan, meski perubahannya langsung tayang tanpa approval.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan_perubahan_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained('layanan')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('ringkasan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan_perubahan_log');
    }
};
