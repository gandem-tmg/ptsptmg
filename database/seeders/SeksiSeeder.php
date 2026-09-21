<?php

namespace Database\Seeders;

use App\Models\Seksi;
use Illuminate\Database\Seeder;

/**
 * Data master seksi/unit, hasil pemetaan dari dokumen
 * "Standar Pelayanan Kemenag Kab. Temanggung Tahun 2026".
 * Urutan & kode sengaja mengikuti urutan tanggung jawab di dokumen tsb.
 */
class SeksiSeeder extends Seeder
{
    public function run(): void
    {
        $seksis = [
            [
                'kode_seksi' => 'TU',
                'nama_seksi' => 'Sub Bagian Tata Usaha',
                'keterangan' => 'Menangani layanan administrasi umum kepegawaian dan ketatausahaan.',
            ],
            [
                'kode_seksi' => 'PDPONTREN',
                'nama_seksi' => 'Seksi Pendidikan Diniyah dan Pondok Pesantren',
                'keterangan' => 'Menangani layanan terkait Madin, LPQ, dan Pondok Pesantren.',
            ],
            [
                'kode_seksi' => 'PAIS',
                'nama_seksi' => 'Seksi Pendidikan Agama Islam',
                'keterangan' => 'Menangani layanan guru agama Islam pada sekolah umum.',
            ],
            [
                'kode_seksi' => 'PENMA',
                'nama_seksi' => 'Seksi Pendidikan Madrasah',
                'keterangan' => 'Menangani layanan perizinan dan administrasi madrasah.',
            ],
            [
                'kode_seksi' => 'BIMAS_ISLAM',
                'nama_seksi' => 'Seksi Bimbingan Masyarakat Islam',
                'keterangan' => 'Menangani layanan bimbingan masyarakat, perkawinan, dan masjid.',
            ],
            [
                'kode_seksi' => 'ZAWA',
                'nama_seksi' => 'Penyelenggara Zakat Wakaf',
                'keterangan' => 'Menangani layanan zakat dan wakaf.',
            ],
            [
                'kode_seksi' => 'KATOLIK',
                'nama_seksi' => 'Penyelenggara Katolik',
                'keterangan' => 'Menangani layanan keagamaan Katolik.',
            ],
            [
                'kode_seksi' => 'BUDDHA',
                'nama_seksi' => 'Penyelenggara Buddha',
                'keterangan' => 'Menangani layanan keagamaan Buddha.',
            ],
        ];

        foreach ($seksis as $seksi) {
            Seksi::updateOrCreate(['kode_seksi' => $seksi['kode_seksi']], $seksi);
        }
    }
}
