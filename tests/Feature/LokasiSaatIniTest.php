<?php

namespace Tests\Feature;

use App\Models\Permohonan;
use App\Models\Seksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Accessor lokasi_saat_ini dipakai di banyak view (detail semua role,
 * kartu daftar, lacak tiket). Test ini menjaga supaya kolom "Sedang
 * Ditangani" tidak kembali kosong.
 */
class LokasiSaatIniTest extends TestCase
{
    use RefreshDatabase;

    public function test_di_seksi_menampilkan_nama_seksi(): void
    {
        $seksi = Seksi::factory()->create(['nama_seksi' => 'Seksi Pendidikan Madrasah']);
        $permohonan = Permohonan::factory()->create([
            'status' => 'didisposisikan',
            'current_seksi_id' => $seksi->id,
        ]);

        $this->assertSame('Seksi Pendidikan Madrasah', $permohonan->lokasi_saat_ini);

        $permohonan->update(['status' => 'diproses_seksi']);
        $this->assertSame('Seksi Pendidikan Madrasah', $permohonan->fresh()->lokasi_saat_ini);
    }

    public function test_di_tangan_ptsp_menampilkan_ptsp(): void
    {
        foreach (['diajukan', 'verifikasi_ptsp', 'selesai_seksi'] as $status) {
            $permohonan = Permohonan::factory()->create(['status' => $status, 'current_seksi_id' => null]);

            $this->assertSame('PTSP', $permohonan->lokasi_saat_ini, "status: {$status}");
        }
    }

    public function test_dikembalikan_menunggu_pemohon(): void
    {
        $permohonan = Permohonan::factory()->create(['status' => 'dikembalikan']);

        $this->assertSame('Pemohon (melengkapi berkas)', $permohonan->lokasi_saat_ini);
    }

    public function test_status_final_menampilkan_strip(): void
    {
        foreach (['selesai', 'ditolak', 'dibatalkan'] as $status) {
            $permohonan = Permohonan::factory()->create(['status' => $status, 'current_seksi_id' => null]);

            $this->assertSame('-', $permohonan->lokasi_saat_ini, "status: {$status}");
        }
    }
}
