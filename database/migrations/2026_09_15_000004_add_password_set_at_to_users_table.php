<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Penanda kapan user terakhir kali SET SENDIRI password-nya secara sadar
     * (lewat register manual, reset password via email, atau form "Set
     * Password" baru di halaman profil) — beda dengan password random yang
     * di-generate sistem saat akun dibuat via Google login.
     *
     * NULL = user ini belum pernah tahu/pilih password-nya sendiri (baik
     * baru daftar via Google, atau akun lama sebelum kolom ini ada — jadi
     * defaultnya aman: tetap wajib current_password kalau ada isinya).
     *
     * Dipakai PasswordController untuk memutuskan apakah form ganti
     * password boleh skip syarat "current_password" (khusus akun yang
     * memang belum pernah punya password yang mereka tahu sendiri).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('password_set_at')->nullable()->after('password');
        });

        // Data existing: anggap semua user LAMA (sudah ada sebelum kolom ini)
        // sudah pernah tahu password mereka sendiri, KECUALI yang akunnya
        // login via Google (provider = google) — supaya perilaku baru ini
        // otomatis berlaku juga untuk akun Google yang sudah terlanjur ada,
        // tanpa perlu migrasi data manual terpisah.
        \Illuminate\Support\Facades\DB::table('users')
            ->where(function ($q) {
                $q->whereNull('provider')->orWhere('provider', '!=', 'google');
            })
            ->update(['password_set_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_set_at');
        });
    }
};
