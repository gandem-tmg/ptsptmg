<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Permohonan;
use App\Models\SurveiJawaban;
use App\Models\SurveiPertanyaan;
use App\Models\SurveiRespon;
use App\Models\User;
use App\Support\StatistikSurvei;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Batch 4 (SKM dinamis) tadinya nguji CRUD pertanyaan (admin), pengisian
 * survei umum (publik) & per-layanan (pemohon), otorisasi hasil, dan
 * perhitungan nilai IKM secara internal.
 *
 * Sejak pengisian SKM dialihkan ke link eksternal (lihat config/skm.php),
 * jalur pengisian survei (formUmum/submitUmum, formPerTiket/submitPerTiket,
 * formPerLayanan/submitPerLayanan) tidak lagi menyimpan apapun ke database
 * dan cuma meneruskan pengguna ke link SKM eksternal — tes-tes di bawah
 * disesuaikan mengikuti perilaku baru itu. CRUD pertanyaan admin, hasil
 * SKM (untuk data historis), dan perhitungan nilai IKM tetap dipertahankan
 * apa adanya karena bagian itu tidak diubah.
 */
class SurveiSkmTest extends TestCase
{
    use RefreshDatabase;

    private function buatPertanyaanSkala4(string $jenisSurvei = 'keduanya'): SurveiPertanyaan
    {
        return SurveiPertanyaan::create([
            'teks_pertanyaan' => 'Bagaimana kesesuaian persyaratan pelayanan?',
            'tipe' => 'skala_4',
            'jenis_survei' => $jenisSurvei,
            'urutan' => 1,
            'aktif' => true,
        ]);
    }

    // ==========================================================
    // CRUD pertanyaan (admin)
    // ==========================================================

    public function test_hanya_admin_yang_bisa_kelola_pertanyaan_survei(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($petugas)->get(route('admin.survei-pertanyaan.index'));

        $response->assertForbidden();
    }

    public function test_admin_bisa_menambah_pertanyaan_survei(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.survei-pertanyaan.store'), [
            'teks_pertanyaan' => 'Bagaimana kecepatan waktu pelayanan?',
            'tipe' => 'skala_4',
            'jenis_survei' => 'per_layanan',
            'urutan' => 2,
            'aktif' => '1',
            'opsi_jawaban_text' => "Sangat Lama\nLama\nCepat\nSangat Cepat",
        ]);

        $response->assertRedirect(route('admin.survei-pertanyaan.index'));
        $this->assertDatabaseHas('survei_pertanyaan', [
            'teks_pertanyaan' => 'Bagaimana kecepatan waktu pelayanan?',
            'tipe' => 'skala_4',
            'jenis_survei' => 'per_layanan',
        ]);

        $pertanyaan = SurveiPertanyaan::first();
        $this->assertSame(['Sangat Lama', 'Lama', 'Cepat', 'Sangat Cepat'], $pertanyaan->opsi_jawaban);
    }

    public function test_hapus_pertanyaan_yang_belum_pernah_dijawab_benar_benar_terhapus(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pertanyaan = $this->buatPertanyaanSkala4();

        $response = $this->actingAs($admin)->delete(route('admin.survei-pertanyaan.destroy', $pertanyaan));

        $response->assertRedirect();
        $this->assertDatabaseMissing('survei_pertanyaan', ['id' => $pertanyaan->id]);
    }

    public function test_hapus_pertanyaan_yang_sudah_dijawab_hanya_dinonaktifkan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pertanyaan = $this->buatPertanyaanSkala4();
        $respon = SurveiRespon::create(['jenis_survei' => 'umum', 'nama_pengisi' => 'Budi']);
        SurveiJawaban::create([
            'survei_respon_id' => $respon->id,
            'survei_pertanyaan_id' => $pertanyaan->id,
            'nilai_rating' => 3,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.survei-pertanyaan.destroy', $pertanyaan));

        $response->assertRedirect();
        $this->assertDatabaseHas('survei_pertanyaan', ['id' => $pertanyaan->id, 'aktif' => false]);
    }

    // ==========================================================
    // Survei umum (publik) — sudah dialihkan ke link SKM eksternal
    // ==========================================================

    public function test_form_survei_umum_diarahkan_ke_link_skm_eksternal(): void
    {
        $response = $this->get(route('survei.umum.create'));

        $response->assertRedirect(config('skm.external_url'));
    }

    public function test_submit_survei_umum_diarahkan_keluar_dan_tidak_menyimpan_apapun(): void
    {
        $pertanyaan = $this->buatPertanyaanSkala4('umum');

        $response = $this->post(route('survei.umum.store'), [
            'nama_pengisi' => 'Warga Temanggung',
            'jawaban' => [$pertanyaan->id => 3],
        ]);

        $response->assertRedirect(config('skm.external_url'));
        $this->assertDatabaseCount('survei_respon', 0);
        $this->assertDatabaseCount('survei_jawaban', 0);
    }

    // ==========================================================
    // Survei per-layanan (pemohon) — sudah dialihkan ke link SKM
    // eksternal, tapi pengecekan kelayakan (pemilik, status selesai,
    // belum pernah disurvei) tetap dipertahankan sebelum diarahkan keluar
    // ==========================================================

    public function test_pemohon_yang_sudah_selesai_diarahkan_ke_link_skm_eksternal(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $layanan = Layanan::factory()->create();
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($pemohon)->get(route('pemohon.permohonan.survei.create', $permohonan));

        $response->assertRedirect(config('skm.external_url'));
    }

    public function test_submit_survei_per_layanan_diarahkan_keluar_dan_tidak_menyimpan_apapun(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $layanan = Layanan::factory()->create();
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'status' => 'selesai',
        ]);
        $pertanyaan = $this->buatPertanyaanSkala4('per_layanan');

        $response = $this->actingAs($pemohon)->post(route('pemohon.permohonan.survei.store', $permohonan), [
            'jawaban' => [$pertanyaan->id => 4],
        ]);

        $response->assertRedirect(config('skm.external_url'));
        $this->assertDatabaseCount('survei_respon', 0);
    }

    public function test_pemohon_tidak_bisa_mengisi_survei_sebelum_permohonan_selesai(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $layanan = Layanan::factory()->create();
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'status' => 'diproses_seksi',
        ]);

        $response = $this->actingAs($pemohon)->get(route('pemohon.permohonan.survei.create', $permohonan));

        $response->assertForbidden();
    }

    public function test_pemohon_tidak_bisa_mengisi_survei_dua_kali_untuk_permohonan_yang_sama(): void
    {
        $pemohon = User::factory()->create(['role' => 'pemohon']);
        $layanan = Layanan::factory()->create();
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohon->id,
            'layanan_id' => $layanan->id,
            'status' => 'selesai',
        ]);
        SurveiRespon::create([
            'jenis_survei' => 'per_layanan',
            'permohonan_id' => $permohonan->id,
            'user_id' => $pemohon->id,
            'nama_pengisi' => $pemohon->name,
        ]);

        $response = $this->actingAs($pemohon)->get(route('pemohon.permohonan.survei.create', $permohonan));

        $response->assertForbidden();
    }

    public function test_pemohon_tidak_bisa_mengisi_survei_permohonan_orang_lain(): void
    {
        $pemohonA = User::factory()->create(['role' => 'pemohon']);
        $pemohonB = User::factory()->create(['role' => 'pemohon']);
        $layanan = Layanan::factory()->create();
        $permohonan = Permohonan::factory()->create([
            'user_id' => $pemohonB->id,
            'layanan_id' => $layanan->id,
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($pemohonA)->get(route('pemohon.permohonan.survei.create', $permohonan));

        $response->assertForbidden();
    }

    // ==========================================================
    // Survei per-tiket (walk-in, tanpa akun) — sudah dialihkan ke link
    // SKM eksternal juga, tetap lewat pengecekan kelayakan tiket dulu.
    // ==========================================================

    public function test_tiket_yang_sudah_selesai_diarahkan_ke_link_skm_eksternal(): void
    {
        $layanan = Layanan::factory()->create();
        $permohonan = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'status' => 'selesai',
        ]);

        $response = $this->get(route('guest.survei.perTiket', $permohonan->no_tiket));

        $response->assertRedirect(config('skm.external_url'));
    }

    public function test_tiket_yang_belum_selesai_tidak_bisa_mengisi_survei(): void
    {
        $layanan = Layanan::factory()->create();
        $permohonan = Permohonan::factory()->create([
            'layanan_id' => $layanan->id,
            'status' => 'diproses_seksi',
        ]);

        $response = $this->get(route('guest.survei.perTiket', $permohonan->no_tiket));

        $response->assertForbidden();
    }

    // ==========================================================
    // Perhitungan nilai IKM & otorisasi hasil
    // ==========================================================

    public function test_nilai_ikm_dihitung_dari_rata_rata_skala_dikali_25(): void
    {
        $pertanyaan = $this->buatPertanyaanSkala4();
        $respon1 = SurveiRespon::create(['jenis_survei' => 'umum', 'nama_pengisi' => 'A']);
        $respon2 = SurveiRespon::create(['jenis_survei' => 'umum', 'nama_pengisi' => 'B']);
        SurveiJawaban::create(['survei_respon_id' => $respon1->id, 'survei_pertanyaan_id' => $pertanyaan->id, 'nilai_rating' => 3]);
        SurveiJawaban::create(['survei_respon_id' => $respon2->id, 'survei_pertanyaan_id' => $pertanyaan->id, 'nilai_rating' => 4]);
        // rata-rata (3+4)/2 = 3.5 -> nilai IKM = 3.5 * 25 = 87.5 -> kategori "Baik" (76.61-88.30)

        $hasil = StatistikSurvei::nilaiIkm();

        $this->assertSame(87.5, $hasil['nilai']);
        $this->assertSame('Baik', $hasil['kategori']);
        $this->assertSame(2, $hasil['jumlah_jawaban']);
    }

    public function test_akses_hasil_skm_dibatasi_untuk_admin_petugas_pimpinan_saja(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugasSeksi = User::factory()->create(['role' => 'petugas_seksi']);
        $pemohon = User::factory()->create(['role' => 'pemohon']);

        $this->actingAs($admin)->get(route('survei.hasil'))->assertOk();
        $this->actingAs($petugasSeksi)->get(route('survei.hasil'))->assertForbidden();
        $this->actingAs($pemohon)->get(route('survei.hasil'))->assertForbidden();
    }

    // ==========================================================
    // Dashboard transparansi publik
    // ==========================================================

    public function test_dashboard_transparansi_tidak_lagi_menampilkan_agregat_skm_internal(): void
    {
        $layanan = Layanan::factory()->create(['nama_layanan' => 'Rekomendasi Haji']);
        $pertanyaan = $this->buatPertanyaanSkala4('per_layanan');
        $permohonan = Permohonan::factory()->create(['layanan_id' => $layanan->id, 'status' => 'selesai']);
        $respon = SurveiRespon::create([
            'jenis_survei' => 'per_layanan',
            'permohonan_id' => $permohonan->id,
            'nama_pengisi' => 'Rahasia Namanya',
        ]);
        SurveiJawaban::create(['survei_respon_id' => $respon->id, 'survei_pertanyaan_id' => $pertanyaan->id, 'nilai_rating' => 4]);

        $response = $this->get(route('transparansi.index'));

        $response->assertOk();
        // Nama layanan tetap muncul (lewat tabel rata-rata waktu penyelesaian,
        // bukan dari blok SKM lagi), tapi nama responden tetap tidak pernah
        // ditampilkan di halaman publik ini.
        $response->assertSee('Rekomendasi Haji');
        $response->assertDontSee('Rahasia Namanya');
        // Blok SKM sekarang cuma nunjuk ke link eksternal, bukan hitung
        // agregat internal lagi.
        $response->assertSee(config('skm.external_url'), false);
    }
}
