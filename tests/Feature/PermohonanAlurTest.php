<?php

namespace Tests\Feature;

use App\Models\Disposisi;
use App\Models\LampiranHasil;
use App\Models\Layanan;
use App\Models\Permohonan;
use App\Models\Persyaratan;
use App\Models\Seksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Cakupan alur inti permohonan: pengajuan -> disposisi -> ditangani seksi
 * -> verifikasi akhir PTSP, termasuk batasan otorisasi per role dan
 * konsistensi riwayat_status (append-only).
 *
 * Sengaja fokus ke PermohonanController karena ini logic paling kritikal
 * di seluruh aplikasi — perubahan di sini paling gampang diam-diam merusak
 * alur kalau tidak ada jaring pengaman.
 */
class PermohonanAlurTest extends TestCase
{
    use RefreshDatabase;

    private function buatLayananDenganPersyaratanWajib(?Seksi $seksi = null): Layanan
    {
        $layanan = Layanan::factory()->create([
            'seksi_id' => $seksi?->id,
        ]);

        Persyaratan::factory()->create([
            'layanan_id' => $layanan->id,
            'nama_persyaratan' => 'KTP',
            'wajib' => true,
        ]);

        return $layanan;
    }

    // ==========================================================
    // Pengajuan (pemohon)
    // ==========================================================

    public function test_pemohon_dapat_mengajukan_permohonan_dengan_lampiran_lengkap(): void
    {
        Storage::fake('public');
        // Notifikasi email di-fake: toMail()-nya melampirkan file lewat
        // storage_path() langsung (bukan lewat disk Storage::fake()), jadi kalau
        // benar-benar dibangun akan gagal nyari file di path testing. Bukan bug
        // di alur permohonan yang sedang diuji di sini, jadi cukup di-skip.
        Notification::fake();

        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $layanan = $this->buatLayananDenganPersyaratanWajib();

        $response = $this->actingAs($pemohon)->post(route('pemohon.permohonan.store'), [
            'layanan_id' => $layanan->id,
            'lampiran' => [
                0 => UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf'),
            ],
        ]);

        $response->assertRedirect(route('pemohon.permohonan.index'));

        $this->assertDatabaseHas('permohonan', [
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'status' => 'diajukan',
        ]);

        $permohonan = Permohonan::first();
        $this->assertNotNull($permohonan->no_tiket);
        $this->assertCount(1, $permohonan->lampiranPermohonan);
        $this->assertCount(1, $permohonan->riwayatStatus);
        $this->assertSame('diajukan', $permohonan->riwayatStatus->first()->status);
    }

    public function test_pemohon_tidak_bisa_mengajukan_tanpa_lampiran_persyaratan_wajib(): void
    {
        Storage::fake('public');

        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $layanan = $this->buatLayananDenganPersyaratanWajib();

        $response = $this->actingAs($pemohon)->post(route('pemohon.permohonan.store'), [
            'layanan_id' => $layanan->id,
            // sengaja tanpa 'lampiran' sama sekali — ini yang tadinya lolos (bug)
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('permohonan', 0);
    }

    // ==========================================================
    // Disposisi (petugas PTSP -> seksi)
    // ==========================================================

    public function test_petugas_dapat_mendisposisikan_ke_seksi_default_layanan(): void
    {
        $seksi = Seksi::factory()->create();
        $layanan = $this->buatLayananDenganPersyaratanWajib($seksi);
        $petugas = User::factory()->create(['role' => 'petugas']);
        $permohonan = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($petugas)->patch(
            route('petugas.permohonan.disposisikan', $permohonan),
            ['catatan' => 'Berkas lengkap, teruskan ke seksi.']
        );

        $response->assertRedirect();
        $permohonan->refresh();

        $this->assertSame($seksi->id, $permohonan->current_seksi_id);
        $this->assertSame('didisposisikan', $permohonan->status);
        $this->assertDatabaseHas('disposisi', [
            'permohonan_id' => $permohonan->id,
            'seksi_id' => $seksi->id,
            'didisposisikan_oleh' => $petugas->id,
        ]);
    }

    public function test_disposisikan_gagal_kalau_layanan_tanpa_seksi_default_dan_tidak_dipilih_manual(): void
    {
        $layanan = $this->buatLayananDenganPersyaratanWajib(); // tanpa seksi default
        $petugas = User::factory()->create(['role' => 'petugas']);
        $permohonan = Permohonan::factory()->create(['layanan_id' => $layanan->id]);

        $response = $this->actingAs($petugas)->patch(
            route('petugas.permohonan.disposisikan', $permohonan),
            []
        );

        $response->assertSessionHasErrors('seksi_id');
        $this->assertDatabaseCount('disposisi', 0);
    }

    public function test_pemohon_tidak_boleh_mendisposisikan(): void
    {
        $layanan = $this->buatLayananDenganPersyaratanWajib();
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $permohonan = Permohonan::factory()->create(['layanan_id' => $layanan->id, 'user_id' => $pemohon->id]);

        $response = $this->actingAs($pemohon)->patch(
            route('petugas.permohonan.disposisikan', $permohonan),
            ['catatan' => 'coba-coba']
        );

        $response->assertForbidden();
    }

    public function test_petugas_dapat_mengembalikan_permohonan_dengan_catatan_wajib(): void
    {
        $layanan = $this->buatLayananDenganPersyaratanWajib();
        $petugas = User::factory()->create(['role' => 'petugas']);
        $permohonan = Permohonan::factory()->create(['layanan_id' => $layanan->id, 'status' => 'diajukan']);

        $this->actingAs($petugas)
            ->patch(route('petugas.permohonan.kembalikan', $permohonan), [])
            ->assertSessionHasErrors('catatan');

        $response = $this->actingAs($petugas)->patch(
            route('petugas.permohonan.kembalikan', $permohonan),
            ['catatan' => 'KTP buram, mohon unggah ulang.']
        );

        $response->assertRedirect();
        $permohonan->refresh();
        $this->assertSame('dikembalikan', $permohonan->status);
    }

    // ==========================================================
    // Ditangani seksi
    // ==========================================================

    public function test_petugas_seksi_dapat_menerima_disposisi_di_seksinya_sendiri(): void
    {
        $seksi = Seksi::factory()->create();
        $layanan = $this->buatLayananDenganPersyaratanWajib($seksi);
        $petugasSeksi = User::factory()->create(['role' => 'petugas_seksi', 'seksi_id' => $seksi->id]);
        $petugasPtsp = User::factory()->create(['role' => 'petugas']);

        $permohonan = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'current_seksi_id' => $seksi->id,
            'status' => 'didisposisikan',
        ]);
        $disposisi = Disposisi::create([
            'permohonan_id' => $permohonan->id,
            'seksi_id' => $seksi->id,
            'didisposisikan_oleh' => $petugasPtsp->id,
            'tanggal_disposisi' => now(),
        ]);

        $response = $this->actingAs($petugasSeksi)->patch(route('seksi.permohonan.terima', $permohonan));

        $response->assertRedirect();
        $permohonan->refresh();
        $disposisi->refresh();

        $this->assertSame('diproses_seksi', $permohonan->status);
        $this->assertSame($petugasSeksi->id, $disposisi->diterima_oleh);
        $this->assertNotNull($disposisi->tanggal_diterima);
    }

    public function test_petugas_seksi_tidak_bisa_menerima_disposisi_seksi_lain(): void
    {
        $seksiTujuan = Seksi::factory()->create();
        $seksiLain = Seksi::factory()->create();
        $layanan = $this->buatLayananDenganPersyaratanWajib($seksiTujuan);

        $petugasSeksiLain = User::factory()->create(['role' => 'petugas_seksi', 'seksi_id' => $seksiLain->id]);

        $permohonan = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'current_seksi_id' => $seksiTujuan->id,
            'status' => 'didisposisikan',
        ]);

        $response = $this->actingAs($petugasSeksiLain)->patch(route('seksi.permohonan.terima', $permohonan));

        $response->assertForbidden();
    }

    public function test_petugas_seksi_dapat_mencatat_progres_tanpa_mengubah_status(): void
    {
        $seksi = Seksi::factory()->create();
        $layanan = $this->buatLayananDenganPersyaratanWajib($seksi);
        $petugasSeksi = User::factory()->create(['role' => 'petugas_seksi', 'seksi_id' => $seksi->id]);

        $permohonan = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'current_seksi_id' => $seksi->id,
            'status' => 'diproses_seksi',
        ]);

        $response = $this->actingAs($petugasSeksi)->patch(
            route('seksi.permohonan.progres', $permohonan),
            ['catatan' => 'Menunggu kehadiran pemohon untuk wawancara.']
        );

        $response->assertRedirect();
        $permohonan->refresh();

        $this->assertSame('diproses_seksi', $permohonan->status);
        $this->assertCount(1, $permohonan->riwayatStatus()->get());
        $this->assertSame(
            'Menunggu kehadiran pemohon untuk wawancara.',
            $permohonan->riwayatStatus()->latest('id')->first()->catatan
        );
    }

    public function test_petugas_seksi_dapat_menyelesaikan_dengan_dokumen_hasil_dan_mengembalikan_ke_ptsp(): void
    {
        Storage::fake('public');

        $seksi = Seksi::factory()->create();
        $layanan = $this->buatLayananDenganPersyaratanWajib($seksi);
        $petugasPtsp = User::factory()->create(['role' => 'petugas']);
        $petugasSeksi = User::factory()->create(['role' => 'petugas_seksi', 'seksi_id' => $seksi->id]);

        $permohonan = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'current_seksi_id' => $seksi->id,
            'status' => 'diproses_seksi',
        ]);
        Disposisi::create([
            'permohonan_id' => $permohonan->id,
            'seksi_id' => $seksi->id,
            'didisposisikan_oleh' => $petugasPtsp->id,
            'diterima_oleh' => $petugasSeksi->id,
            'tanggal_disposisi' => now()->subDay(),
            'tanggal_diterima' => now()->subDay(),
        ]);

        $response = $this->actingAs($petugasSeksi)->post(
            route('seksi.permohonan.selesai', $permohonan),
            [
                'catatan' => 'Selesai diproses.',
                'nama_dokumen' => 'Surat Rekomendasi',
                'dokumen_hasil' => UploadedFile::fake()->create('hasil.pdf', 200, 'application/pdf'),
            ]
        );

        $response->assertRedirect();
        $permohonan->refresh();

        $this->assertSame('selesai_seksi', $permohonan->status);
        $this->assertNull($permohonan->current_seksi_id);
        $this->assertCount(1, $permohonan->lampiranHasil);

        $disposisi = $permohonan->disposisi()->latest('tanggal_disposisi')->first();
        $this->assertNotNull($disposisi->tanggal_selesai_seksi);
    }

    // ==========================================================
    // Verifikasi akhir (PTSP) & dokumen ke akun pemohon
    // ==========================================================

    public function test_petugas_dapat_verifikasi_akhir_setelah_selesai_seksi(): void
    {
        $layanan = $this->buatLayananDenganPersyaratanWajib();
        $petugas = User::factory()->create(['role' => 'petugas']);
        $permohonan = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'status' => 'selesai_seksi',
        ]);

        $response = $this->actingAs($petugas)->patch(
            route('petugas.permohonan.verifikasiAkhir', $permohonan),
            ['catatan' => 'Sesuai, siap diserahkan.']
        );

        $response->assertRedirect();
        $permohonan->refresh();
        $this->assertSame('selesai', $permohonan->status);
    }

    public function test_dokumen_hasil_muncul_di_menu_dokumen_saya_pemohon_setelah_selesai(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $layanan = $this->buatLayananDenganPersyaratanWajib();
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'status' => 'selesai',
        ]);
        LampiranHasil::create([
            'permohonan_id' => $permohonan->id,
            'nama_dokumen' => 'SK Selesai',
            'file_path' => 'lampiran_hasil/dummy.pdf',
            'diunggah_oleh' => $pemohon->id,
            'tanggal_unggah' => now(),
        ]);

        $response = $this->actingAs($pemohon)->get(route('pemohon.dokumen.index'));

        $response->assertOk();
        $response->assertSee('SK Selesai');
    }

    public function test_dokumen_hasil_dari_permohonan_yang_belum_selesai_tidak_muncul_di_dokumen_saya(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $layanan = $this->buatLayananDenganPersyaratanWajib();
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'status' => 'selesai_seksi', // belum diverifikasi akhir PTSP
        ]);
        LampiranHasil::create([
            'permohonan_id' => $permohonan->id,
            'nama_dokumen' => 'Draft Belum Final',
            'file_path' => 'lampiran_hasil/draft.pdf',
            'diunggah_oleh' => $pemohon->id,
            'tanggal_unggah' => now(),
        ]);

        $response = $this->actingAs($pemohon)->get(route('pemohon.dokumen.index'));

        $response->assertOk();
        $response->assertDontSee('Draft Belum Final');
    }

    // ==========================================================
    // Batasan otorisasi lintas role
    // ==========================================================

    public function test_pemohon_tidak_bisa_mengakses_permohonan_milik_orang_lain(): void
    {
        $pemohonA = User::factory()->create(['role' => 'pemohon']);
        $pemohonB = User::factory()->create(['role' => 'pemohon']);
        $layanan = $this->buatLayananDenganPersyaratanWajib();
        $permohonan = Permohonan::factory()->create(['layanan_id' => $layanan->id, 'user_id' => $pemohonB->id]);

        $response = $this->actingAs($pemohonA)->get(route('pemohon.permohonan.show', $permohonan));

        $response->assertForbidden();
    }

    public function test_petugas_seksi_tidak_bisa_mengakses_permohonan_yang_seksinya_belum_pernah_menangani(): void
    {
        $seksi = Seksi::factory()->create();
        $petugasSeksi = User::factory()->create(['role' => 'petugas_seksi', 'seksi_id' => $seksi->id]);
        $layanan = $this->buatLayananDenganPersyaratanWajib();
        $permohonan = Permohonan::factory()->create(['layanan_id' => $layanan->id]);

        $response = $this->actingAs($petugasSeksi)->get(route('seksi.permohonan.show', $permohonan));

        $response->assertForbidden();
    }

    public function test_update_manual_ptsp_hanya_menerima_transisi_status_tertentu(): void
    {
        $layanan = $this->buatLayananDenganPersyaratanWajib();
        $petugas = User::factory()->create(['role' => 'petugas']);
        $permohonan = Permohonan::factory()->create(['layanan_id' => $layanan->id, 'status' => 'didisposisikan']);

        $response = $this->actingAs($petugas)->patch(
            route('petugas.permohonan.updateManual', $permohonan),
            ['status' => 'selesai', 'catatan' => 'Coba lompat status.']
        );

        $response->assertSessionHasErrors('status');
    }

    // ==========================================================
    // Konsistensi riwayat_status di sepanjang alur
    // ==========================================================

    public function test_riwayat_status_tercatat_lengkap_disepanjang_alur_disposisi(): void
    {
        Storage::fake('public');
        Notification::fake();

        $seksi = Seksi::factory()->create();
        $layanan = $this->buatLayananDenganPersyaratanWajib($seksi);
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $petugas = User::factory()->create(['role' => 'petugas']);
        $petugasSeksi = User::factory()->create(['role' => 'petugas_seksi', 'seksi_id' => $seksi->id]);

        $this->actingAs($pemohon)->post(route('pemohon.permohonan.store'), [
            'layanan_id' => $layanan->id,
            'lampiran' => [0 => UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf')],
        ]);
        $permohonan = Permohonan::first();

        $this->actingAs($petugas)->patch(route('petugas.permohonan.disposisikan', $permohonan), []);
        $this->actingAs($petugasSeksi)->patch(route('seksi.permohonan.terima', $permohonan));
        $this->actingAs($petugasSeksi)->post(route('seksi.permohonan.selesai', $permohonan), ['catatan' => 'Selesai.']);
        $this->actingAs($petugas)->patch(route('petugas.permohonan.verifikasiAkhir', $permohonan), []);

        $permohonan->refresh();

        $this->assertSame('selesai', $permohonan->status);
        $this->assertSame(
            ['diajukan', 'didisposisikan', 'diproses_seksi', 'selesai_seksi', 'selesai'],
            $permohonan->riwayatStatus()->orderBy('created_at')->orderBy('id')->pluck('status')->all()
        );
    }
}
