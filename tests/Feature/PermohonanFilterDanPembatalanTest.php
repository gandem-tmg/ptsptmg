<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Permohonan;
use App\Models\Seksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Batch 2: filter lanjutan di halaman index (status, seksi, rentang tanggal
 * pengajuan) dan pembatalan mandiri oleh pemohon (dibatasi sampai status
 * 'didisposisikan', sebelum diterima seksi).
 */
class PermohonanFilterDanPembatalanTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================================
    // Filter lanjutan
    // ==========================================================

    public function test_filter_status_hanya_menampilkan_permohonan_dengan_status_terpilih(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $layanan = Layanan::factory()->create();

        $sudahSelesai = Permohonan::factory()->create(['layanan_id' => $layanan->id, 'status' => 'selesai']);
        $masihDiajukan = Permohonan::factory()->create(['layanan_id' => $layanan->id, 'status' => 'diajukan']);

        $response = $this->actingAs($petugas)->get(route('petugas.permohonan.index', ['status' => 'selesai']));

        $response->assertOk();
        $response->assertSee($sudahSelesai->no_tiket);
        $response->assertDontSee($masihDiajukan->no_tiket);
    }

    public function test_filter_seksi_hanya_menampilkan_permohonan_di_seksi_terpilih(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $layanan = Layanan::factory()->create();
        $seksiA = Seksi::factory()->create();
        $seksiB = Seksi::factory()->create();

        $diSeksiA = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'current_seksi_id' => $seksiA->id,
            'status' => 'didisposisikan',
        ]);
        $diSeksiB = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'current_seksi_id' => $seksiB->id,
            'status' => 'didisposisikan',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.permohonan.index', ['seksi_id' => $seksiA->id]));

        $response->assertOk();
        $response->assertSee($diSeksiA->no_tiket);
        $response->assertDontSee($diSeksiB->no_tiket);
    }

    public function test_filter_rentang_tanggal_pengajuan(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $layanan = Layanan::factory()->create();

        $dalamRentang = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'tanggal_pengajuan' => '2026-06-15',
        ]);
        $diLuarRentang = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'tanggal_pengajuan' => '2026-01-01',
        ]);

        $response = $this->actingAs($petugas)->get(route('petugas.permohonan.index', [
            'tanggal_dari' => '2026-06-01',
            'tanggal_sampai' => '2026-06-30',
        ]));

        $response->assertOk();
        $response->assertSee($dalamRentang->no_tiket);
        $response->assertDontSee($diLuarRentang->no_tiket);
    }

    public function test_filter_tanggal_dengan_format_tidak_valid_tidak_bikin_halaman_error(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        Permohonan::factory()->create(['layanan_id' => Layanan::factory()->create()->id]);

        $response = $this->actingAs($petugas)->get(route('petugas.permohonan.index', [
            'tanggal_dari' => 'bukan-tanggal',
        ]));

        // Format tidak valid diabaikan, bukan bikin 500 — semua data tetap tampil.
        $response->assertOk();
    }

    // ==========================================================
    // Pembatalan mandiri oleh pemohon
    // ==========================================================

    public function test_pemohon_dapat_membatalkan_permohonan_saat_masih_diajukan(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $layanan = Layanan::factory()->create();
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($pemohon)->delete(route('pemohon.permohonan.batalkan', $permohonan));

        $response->assertRedirect(route('pemohon.permohonan.index'));
        $permohonan->refresh();
        $this->assertSame('dibatalkan', $permohonan->status);
        $this->assertSame(
            'dibatalkan',
            $permohonan->riwayatStatus()->latest('id')->first()->status
        );
    }

    public function test_pemohon_dapat_membatalkan_permohonan_saat_sudah_didisposisikan(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $seksi = Seksi::factory()->create();
        $layanan = Layanan::factory()->create(['seksi_id' => $seksi->id]);
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'current_seksi_id' => $seksi->id,
            'status' => 'didisposisikan',
        ]);

        $response = $this->actingAs($pemohon)->delete(route('pemohon.permohonan.batalkan', $permohonan));

        $response->assertRedirect(route('pemohon.permohonan.index'));
        $permohonan->refresh();
        $this->assertSame('dibatalkan', $permohonan->status);
        // current_seksi_id dikosongkan lagi karena batal sebelum benar-benar ditangani.
        $this->assertNull($permohonan->current_seksi_id);
    }

    public function test_pemohon_tidak_bisa_membatalkan_setelah_diterima_dan_diproses_seksi(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $seksi = Seksi::factory()->create();
        $layanan = Layanan::factory()->create(['seksi_id' => $seksi->id]);
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'current_seksi_id' => $seksi->id,
            'status' => 'diproses_seksi',
        ]);

        $response = $this->actingAs($pemohon)->delete(route('pemohon.permohonan.batalkan', $permohonan));

        $response->assertSessionHasErrors('batal');
        $permohonan->refresh();
        $this->assertSame('diproses_seksi', $permohonan->status);
    }

    public function test_pemohon_tidak_bisa_membatalkan_permohonan_milik_orang_lain(): void
    {
        $pemohonA = User::factory()->create(['role' => 'pemohon']);
        $pemohonB = User::factory()->create(['role' => 'pemohon']);
        $layanan = Layanan::factory()->create();
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohonB->id,
            'layanan_id' => $layanan->id,
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($pemohonA)->delete(route('pemohon.permohonan.batalkan', $permohonan));

        $response->assertForbidden();
        $permohonan->refresh();
        $this->assertSame('diajukan', $permohonan->status);
    }
}
