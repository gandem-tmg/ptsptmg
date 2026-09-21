<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Permohonan;
use App\Models\Seksi;
use App\Models\User;
use App\Support\StatistikPermohonan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Batch 3: halaman Statistik (internal, admin & petugas) — otorisasi,
 * filter, export CSV — dan Dashboard Transparansi (publik, agregat saja).
 */
class StatistikTest extends TestCase
{
    use RefreshDatabase;

    private function buatPermohonanSelesai(Layanan $layanan, string $tanggalPengajuan, int $hariSampaiSelesai): Permohonan
    {
        $permohonan = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'tanggal_pengajuan' => $tanggalPengajuan,
            'status' => 'selesai',
        ]);

        $riwayat = $permohonan->riwayatStatus()->create(['status' => 'selesai']);
        $riwayat->created_at = Carbon::parse($tanggalPengajuan)->addDays($hariSampaiSelesai);
        $riwayat->save();

        return $permohonan;
    }

    // ==========================================================
    // Otorisasi akses halaman statistik
    // ==========================================================

    public function test_admin_bisa_akses_statistik_dan_melihat_kartu_total_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('statistics.index'));

        $response->assertOk();
        $response->assertSee('Total Users');
    }

    public function test_petugas_bisa_akses_statistik_tapi_tidak_melihat_kartu_total_users(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($petugas)->get(route('statistics.index'));

        $response->assertOk();
        $response->assertDontSee('Total Users');
    }

    public function test_petugas_seksi_tidak_bisa_akses_statistik(): void
    {
        $petugasSeksi = User::factory()->create(['role' => 'petugas_seksi']);

        $response = $this->actingAs($petugasSeksi)->get(route('statistics.index'));

        $response->assertForbidden();
    }

    public function test_pimpinan_bisa_akses_statistik_tapi_tidak_melihat_kartu_total_users(): void
    {
        $pimpinan = User::factory()->create(['role' => 'pimpinan']);

        $response = $this->actingAs($pimpinan)->get(route('statistics.index'));

        $response->assertOk();
        $response->assertDontSee('Total Users');
    }

    public function test_pemohon_tidak_bisa_akses_statistik(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);

        $response = $this->actingAs($pemohon)->get(route('statistics.index'));

        $response->assertForbidden();
    }

    // ==========================================================
    // Filter & perhitungan
    // ==========================================================

    public function test_filter_bulan_membatasi_total_permohonan_yang_dihitung(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $layanan = Layanan::factory()->create();

        Permohonan::factory()->create(['layanan_id' => $layanan->id, 'tanggal_pengajuan' => '2026-06-10']);
        Permohonan::factory()->create(['layanan_id' => $layanan->id, 'tanggal_pengajuan' => '2026-01-05']);

        $response = $this->actingAs($petugas)->get(route('statistics.index', ['bulan' => '2026-06']));

        $response->assertOk();
        // Total permohonan yang dihitung dalam filter Juni 2026 harus 1.
        $response->assertViewHas('totalPermohonan', 1);
    }

    public function test_rata_rata_hari_selesai_per_layanan_dihitung_dengan_benar(): void
    {
        $layanan = Layanan::factory()->create();
        $this->buatPermohonanSelesai($layanan, '2026-06-01', 4);
        $this->buatPermohonanSelesai($layanan, '2026-06-05', 6);
        // rata-rata seharusnya (4 + 6) / 2 = 5 hari

        $hasil = StatistikPermohonan::rataRataHariSelesaiPerLayanan(Permohonan::query());

        $this->assertCount(1, $hasil);
        $this->assertSame(5.0, $hasil->first()->rata_rata_hari);
        $this->assertSame(2, $hasil->first()->jumlah_selesai);
    }

    public function test_export_csv_berisi_ringkasan_per_layanan(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $layanan = Layanan::factory()->create(['nama_layanan' => 'Legalisir Ijazah']);
        Permohonan::factory()->create(['layanan_id' => $layanan->id]);

        $response = $this->actingAs($petugas)->get(route('statistics.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('Layanan,Jumlah Permohonan,Jumlah Selesai,Rata-rata Hari Proses', $content);
        $this->assertStringContainsString('Legalisir Ijazah', $content);
    }

    public function test_pemohon_tidak_bisa_export_statistik(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);

        $response = $this->actingAs($pemohon)->get(route('statistics.export'));

        $response->assertForbidden();
    }

    // ==========================================================
    // Dashboard transparansi publik
    // ==========================================================

    public function test_dashboard_transparansi_bisa_diakses_tanpa_login(): void
    {
        $response = $this->get(route('transparansi.index'));

        $response->assertOk();
        $response->assertSee('Dashboard Transparansi PTSP');
    }

    public function test_dashboard_transparansi_menghitung_total_bulan_ini_dengan_benar(): void
    {
        $layanan = Layanan::factory()->create();
        Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'tanggal_pengajuan' => now()->startOfMonth()->addDays(2),
        ]);
        Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'tanggal_pengajuan' => now()->subMonths(3),
        ]);

        $response = $this->get(route('transparansi.index'));

        $response->assertOk();
        $response->assertViewHas('totalBulanIni', 1);
    }
}
