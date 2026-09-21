<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Master seksi harus ada duluan sebelum user petugas_seksi & layanan dibuat.
        $this->call(SeksiSeeder::class);

        $seksiPenma = \App\Models\Seksi::where('kode_seksi', 'PENMA')->first();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Petugas PTSP',
            'email' => 'petugas@example.com',
            'role' => 'petugas',
        ]);

        User::factory()->create([
            'name' => 'Petugas Seksi Pendidikan Madrasah',
            'email' => 'petugas.penma@example.com',
            'role' => 'petugas_seksi',
            'seksi_id' => $seksiPenma?->id,
        ]);

        User::factory()->create([
            'name' => 'Kepala Kantor',
            'email' => 'pimpinan@example.com',
            'role' => 'pimpinan',
        ]);

        User::factory()->create([
            'name' => 'Pemohon User',
            'email' => 'pemohon@example.com',
            'role' => 'pemohon',
        ]);

        // 45 layanan riil dari dokumen Standar Pelayanan Kemenag Kab.
        // Temanggung (nama, seksi, & persyaratan asli dari dokumen).
        // tipe_pelaksanaan masih tebakan awal berdasar kata kunci —
        // silakan dikoreksi lewat panel admin sesuai hasil klasifikasi.
        $this->call(LayananSeeder::class);
        $this->call(LayananDeskripsiSeeder::class);

        // Contoh template surat, supaya alur generate-surat & TTE langsung
        // bisa dicoba tanpa perlu bikin template manual dulu.
        $layananContoh = \App\Models\Layanan::where('kode_layanan', 'PENMA-05')->first();
        if ($layananContoh) {
            \App\Models\TemplateSurat::updateOrCreate(
                ['layanan_id' => $layananContoh->id, 'judul_template' => 'Surat Rekomendasi Bantuan Madrasah'],
                ['isi_template' => "Yang bertanda tangan di bawah ini, Kepala Kantor Kementerian Agama Kabupaten Temanggung, dengan ini menerangkan bahwa:\n\nNama : {{nama_pemohon}}\nNIK : {{nik}}\nAlamat : {{alamat_pemohon}}\n\nDengan Nomor Tiket Pengajuan {{no_tiket}}, telah memenuhi persyaratan untuk memperoleh {{nama_layanan}}.\n\nDemikian surat rekomendasi ini dibuat untuk dipergunakan sebagaimana mestinya.\n\nTemanggung, {{tanggal_surat}}", 'aktif' => true]
            );
        }
    }
}
