<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Persyaratan;
use App\Models\Seksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Cakupan fitur "petugas seksi kelola layanan seksi sendiri": daftar dan
 * detail cuma berisi layanan seksi sendiri, layanan seksi lain ditolak
 * 403, field identitas/klasifikasi layanan tidak bisa diubah lewat sini
 * walau dikirim di payload, persyaratan bebas tambah/edit/hapus, dan
 * setiap update yang benar-benar mengubah sesuatu tercatat ke log.
 */
class SeksiLayananTest extends TestCase
{
    use RefreshDatabase;

    private function buatPetugasSeksi(Seksi $seksi): User
    {
        return User::factory()->create(['role' => 'petugas_seksi', 'seksi_id' => $seksi->id]);
    }

    public function test_daftar_hanya_berisi_layanan_seksi_sendiri(): void
    {
        $seksiSaya = Seksi::factory()->create();
        $seksiLain = Seksi::factory()->create();
        $petugas = $this->buatPetugasSeksi($seksiSaya);

        $milikSaya = Layanan::factory()->create(['seksi_id' => $seksiSaya->id, 'nama_layanan' => 'Layanan Milik Saya']);
        Layanan::factory()->create(['seksi_id' => $seksiLain->id, 'nama_layanan' => 'Layanan Seksi Lain']);

        $response = $this->actingAs($petugas)->get(route('seksi.layanan.index'));

        $response->assertOk();
        $response->assertSee('Layanan Milik Saya');
        $response->assertDontSee('Layanan Seksi Lain');
    }

    public function test_tidak_bisa_membuka_layanan_seksi_lain(): void
    {
        $seksiSaya = Seksi::factory()->create();
        $seksiLain = Seksi::factory()->create();
        $petugas = $this->buatPetugasSeksi($seksiSaya);

        $layananSeksiLain = Layanan::factory()->create(['seksi_id' => $seksiLain->id]);

        $this->actingAs($petugas)->get(route('seksi.layanan.show', $layananSeksiLain))->assertForbidden();
        $this->actingAs($petugas)->get(route('seksi.layanan.edit', $layananSeksiLain))->assertForbidden();
        $this->actingAs($petugas)->put(route('seksi.layanan.update', $layananSeksiLain), [
            'deskripsi' => 'coba ubah',
        ])->assertForbidden();

        $this->assertNotEquals('coba ubah', $layananSeksiLain->fresh()->deskripsi);
    }

    public function test_bisa_mengubah_deskripsi_dan_standar_pelayanan_layanan_sendiri(): void
    {
        $seksi = Seksi::factory()->create();
        $petugas = $this->buatPetugasSeksi($seksi);
        $layanan = Layanan::factory()->create(['seksi_id' => $seksi->id, 'deskripsi' => 'lama']);

        $response = $this->actingAs($petugas)->put(route('seksi.layanan.update', $layanan), [
            'deskripsi' => 'Deskripsi baru sesuai regulasi terbaru',
            'jangka_waktu_pelayanan' => '3 hari kerja',
            'perlu_dokumen_hasil' => '1',
        ]);

        $response->assertRedirect(route('seksi.layanan.show', $layanan));
        $layanan->refresh();
        $this->assertSame('Deskripsi baru sesuai regulasi terbaru', $layanan->deskripsi);
        $this->assertSame('3 hari kerja', $layanan->jangka_waktu_pelayanan);
        $this->assertTrue($layanan->perlu_dokumen_hasil);
    }

    public function test_field_identitas_dan_klasifikasi_tidak_berubah_walau_dikirim(): void
    {
        $seksiSaya = Seksi::factory()->create();
        $seksiLain = Seksi::factory()->create();
        $petugas = $this->buatPetugasSeksi($seksiSaya);

        $layanan = Layanan::factory()->create([
            'seksi_id' => $seksiSaya->id,
            'nama_layanan' => 'Nama Asli',
            'kode_layanan' => 'ASLI01',
            'kategori' => 'pendidikan_madrasah',
        ]);

        $this->actingAs($petugas)->put(route('seksi.layanan.update', $layanan), [
            'nama_layanan' => 'Nama Diubah Paksa',
            'kode_layanan' => 'HACK01',
            'seksi_id' => $seksiLain->id,
            'kategori' => 'kepegawaian',
            'deskripsi' => 'update sah',
        ])->assertRedirect();

        $layanan->refresh();
        $this->assertSame('Nama Asli', $layanan->nama_layanan);
        $this->assertSame('ASLI01', $layanan->kode_layanan);
        $this->assertSame($seksiSaya->id, $layanan->seksi_id);
        $this->assertSame('pendidikan_madrasah', $layanan->kategori);
        $this->assertSame('update sah', $layanan->deskripsi);
    }

    public function test_persyaratan_bebas_ditambah_diubah_dan_dihapus(): void
    {
        $seksi = Seksi::factory()->create();
        $petugas = $this->buatPetugasSeksi($seksi);
        $layanan = Layanan::factory()->create(['seksi_id' => $seksi->id]);

        $tetap = Persyaratan::factory()->create(['layanan_id' => $layanan->id, 'nama_persyaratan' => 'KTP', 'wajib' => true]);
        $dihapus = Persyaratan::factory()->create(['layanan_id' => $layanan->id, 'nama_persyaratan' => 'KK', 'wajib' => false]);

        $this->actingAs($petugas)->put(route('seksi.layanan.update', $layanan), [
            'persyaratan' => [
                ['id' => $tetap->id, 'nama_persyaratan' => 'KTP (diperbarui)', 'wajib' => '1'],
                ['nama_persyaratan' => 'Surat Keterangan Baru', 'wajib' => '1'],
            ],
        ])->assertRedirect();

        $sisaNama = $layanan->fresh()->persyaratan->pluck('nama_persyaratan')->all();
        $this->assertContains('KTP (diperbarui)', $sisaNama);
        $this->assertContains('Surat Keterangan Baru', $sisaNama);
        $this->assertCount(2, $sisaNama);
        $this->assertDatabaseMissing('persyaratan', ['id' => $dihapus->id]);
    }

    public function test_update_yang_mengubah_sesuatu_tercatat_ke_log(): void
    {
        $seksi = Seksi::factory()->create();
        $petugas = $this->buatPetugasSeksi($seksi);
        $layanan = Layanan::factory()->create(['seksi_id' => $seksi->id, 'deskripsi' => 'lama']);

        $this->actingAs($petugas)->put(route('seksi.layanan.update', $layanan), [
            'deskripsi' => 'baru',
        ]);

        $this->assertDatabaseHas('layanan_perubahan_log', [
            'layanan_id' => $layanan->id,
            'user_id' => $petugas->id,
        ]);
        $this->assertStringContainsString('Deskripsi diubah', $layanan->perubahanLog()->first()->ringkasan);
    }

    public function test_update_tanpa_perubahan_apapun_tidak_membuat_log(): void
    {
        $seksi = Seksi::factory()->create();
        $petugas = $this->buatPetugasSeksi($seksi);
        // perlu_dokumen_hasil default-nya true di DB (lihat migration
        // add_perlu_dokumen_hasil_to_layanan_table). Checkbox unchecked
        // tidak dikirim browser sama sekali, jadi untuk mensimulasikan
        // "form disubmit ulang tanpa diubah apa pun", request di bawah
        // WAJIB tetap menyertakan perlu_dokumen_hasil => '1' — persis
        // seperti form edit yang selalu mengirim ulang checked-state saat
        // ini. Tanpa ini, hilangnya field tersebut dibaca controller
        // sebagai true -> false, yang memang perubahan sungguhan.
        $layanan = Layanan::factory()->create([
            'seksi_id' => $seksi->id,
            'deskripsi' => 'sama saja',
            'perlu_dokumen_hasil' => true,
        ]);

        $this->actingAs($petugas)->put(route('seksi.layanan.update', $layanan), [
            'deskripsi' => 'sama saja',
            'perlu_dokumen_hasil' => '1',
        ]);

        $this->assertDatabaseCount('layanan_perubahan_log', 0);
    }
}
