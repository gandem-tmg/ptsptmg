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
            $table->string('nama')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nik', 16)->nullable()->unique();
            $table->string('no_hp', 15)->nullable();
            $table->string('ktp_path')->nullable();
            $table->text('deskripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->dropColumn(['nama', 'alamat', 'nik', 'no_hp', 'ktp_path', 'deskripsi']);
        });
    }
};
