<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Permohonan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test untuk WebhookController (endpoint /webhook/permohonan/*) yang menerima
 * request dari aplikasi Layanan Madrasah.
 *
 * Ditulis sesi 4 (28 Agt 2026) memenuhi item v2 §3e / v3 §2.3 di
 * HANDOFF_NOTES_v3.md: "unit Job dengan Http::fake(), feature test
 * WebhookController dengan payload tipe_layanan valid & invalid, termasuk
 * test idempotensi id sama+tipe beda".
 *
 * PENTING: belum pernah dijalankan (tidak ada PHP di sandbox Claude yang
 * menulis file ini) — jalankan `php artisan test --filter=WebhookController`
 * di server/CI sebelum dipercaya. Kalau ada kegagalan, kemungkinan besar
 * karena asumsi skema/route di sini sedikit meleset dari kondisi terbaru
 * repo (mis. nama route berubah) — cek pesan error phpunit dan sesuaikan.
 */
class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $secret = 'test-webhook-secret-rahasia';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.layanan_madrasah.webhook_secret' => $this->secret]);
        // Allowlist kosong secara default di test lain -> middleware skip
        // pengecekan IP kecuali test yang eksplisit mengisinya.
        config(['services.layanan_madrasah.webhook_ip_allowlist' => []]);
        config(['services.layanan_madrasah.default_layanan_id' => 26]);
        config(['services.layanan_madrasah.layanan_id_map' => []]);

        // layanan_id pada permohonan adalah FK -> perlu baris `layanan` id=26.
        // Gunakan forceCreate agar `id` tidak diabaikan oleh mass assignment,
        // karena model Layanan tidak mendefinisikan `id` sebagai fillable.
        Layanan::query()->forceCreate([
            'id' => 26,
            'nama_layanan' => 'Surat Rekomendasi',
            'kode_layanan' => 'REKOM',
        ]);
    }

    private function headers(array $extra = []): array
    {
        return array_merge([
            'X-Webhook-Secret' => $this->secret,
        ], $extra);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'id_layanan' => 5,
            'tipe_layanan' => 'rekomendasi',
            'nama_madrasah' => 'MTs Contoh',
            'nsm' => '12123456789',
            'link_drive_berkas' => 'https://drive.google.com/file/d/abc',
        ], $overrides);
    }

    public function test_tiket_valid_untuk_setiap_tipe_layanan_berhasil_dibuat(): void
    {
        $tipeValid = [
            'rekomendasi', 'rekomendasi_kamad', 'pindah_sekolah',
            'input_siswa', 'reaktivasi', 'reset_verval',
        ];

        foreach ($tipeValid as $tipe) {
            $response = $this->postJson(
                '/webhook/permohonan/tiket',
                $this->payload(['tipe_layanan' => $tipe, 'id_layanan' => 1]),
                $this->headers()
            );

            $response->assertStatus(201)
                ->assertJson(['success' => true])
                ->assertJsonStructure(['success', 'nomor_tiket', 'message']);

            $this->assertDatabaseHas('permohonan', [
                'sumber' => 'layanan_madrasah',
                'tipe_layanan_asal' => $tipe,
                'id_layanan_asal' => '1',
                'no_tiket' => $response->json('nomor_tiket'),
            ]);
        }
    }

    public function test_tipe_layanan_tidak_valid_ditolak_422(): void
    {
        $response = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(['tipe_layanan' => 'tipe_yang_tidak_ada']),
            $this->headers()
        );

        $response->assertStatus(422)
            ->assertJson(['success' => false])
            ->assertJsonValidationErrors(['tipe_layanan']);

        $this->assertDatabaseCount('permohonan', 0);
    }

    public function test_tipe_layanan_kosong_ditolak_422(): void
    {
        $response = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(['tipe_layanan' => null]),
            $this->headers()
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tipe_layanan']);
    }

    public function test_tanpa_header_secret_ditolak_401(): void
    {
        $response = $this->postJson('/webhook/permohonan/tiket', $this->payload());

        $response->assertStatus(401);
        $this->assertDatabaseCount('permohonan', 0);
    }

    public function test_secret_salah_ditolak_401(): void
    {
        $response = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(),
            $this->headers(['X-Webhook-Secret' => 'salah-sekali'])
        );

        $response->assertStatus(401);
    }

    public function test_ip_di_luar_allowlist_ditolak_403(): void
    {
        config(['services.layanan_madrasah.webhook_ip_allowlist' => ['203.0.113.0/24']]);

        $response = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(),
            $this->headers()
        );

        $response->assertStatus(403);
        $this->assertDatabaseCount('permohonan', 0);
    }

    public function test_ip_di_dalam_allowlist_diloloskan(): void
    {
        // Test client default berasal dari 127.0.0.1, jadi allowlist yang
        // mencakup 127.0.0.1/32 seharusnya lolos.
        config(['services.layanan_madrasah.webhook_ip_allowlist' => ['127.0.0.1/32']]);

        $response = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(),
            $this->headers()
        );

        $response->assertStatus(201);
    }

    public function test_webhook_menggunakan_format_tiket_baru_ptsp(): void
    {
        $response = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(['id_layanan' => 21, 'tipe_layanan' => 'rekomendasi']),
            $this->headers()
        )->assertStatus(201);

        $nomor = $response->json('nomor_tiket');

        $this->assertMatchesRegularExpression('/^\d{6}-\d{3}$/', $nomor);
        $this->assertStringNotContainsString('TKT-', $nomor);
    }

    public function test_idempotensi_id_dan_tipe_sama_mengembalikan_tiket_yang_sama(): void
    {
        $first = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(['id_layanan' => 7, 'tipe_layanan' => 'rekomendasi']),
            $this->headers()
        )->assertStatus(201);

        $second = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(['id_layanan' => 7, 'tipe_layanan' => 'rekomendasi']),
            $this->headers()
        )->assertStatus(201);

        $this->assertSame(
            $first->json('nomor_tiket'),
            $second->json('nomor_tiket'),
            'Request kedua dengan id_layanan+tipe_layanan sama harus mengembalikan no_tiket yang sama (idempotensi), bukan membuat baris permohonan baru.'
        );

        $this->assertDatabaseCount('permohonan', 1);
    }

    /**
     * Regression test untuk bug krusial yang diperbaiki migration
     * 2026_08_28_000002 (PTSP): id_layanan yang sama TAPI tipe_layanan
     * berbeda TIDAK BOLEH dianggap permohonan yang sama / saling menimpa
     * nomor tiket satu sama lain.
     */
    public function test_id_layanan_sama_tapi_tipe_berbeda_tidak_saling_tabrakan(): void
    {
        $rekomendasi = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(['id_layanan' => 5, 'tipe_layanan' => 'rekomendasi']),
            $this->headers()
        )->assertStatus(201);

        $inputSiswa = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(['id_layanan' => 5, 'tipe_layanan' => 'input_siswa']),
            $this->headers()
        )->assertStatus(201);

        $this->assertNotSame(
            $rekomendasi->json('nomor_tiket'),
            $inputSiswa->json('nomor_tiket'),
            'id_layanan=5 pada tipe rekomendasi dan input_siswa adalah dua pengajuan yang sama sekali berbeda, tiketnya wajib berbeda.'
        );

        $this->assertDatabaseCount('permohonan', 2);
    }

    public function test_update_rekomendasi_fase_2_berhasil(): void
    {
        $create = $this->postJson(
            '/webhook/permohonan/tiket',
            $this->payload(['id_layanan' => 9, 'tipe_layanan' => 'rekomendasi']),
            $this->headers()
        )->assertStatus(201);

        $noTiket = $create->json('nomor_tiket');

        $response = $this->postJson(
            '/webhook/permohonan/rekomendasi',
            [
                'no_tiket' => $noTiket,
                'link_drive_rekom' => 'https://drive.google.com/file/d/rekom-xyz',
            ],
            $this->headers()
        );

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('permohonan', [
            'no_tiket' => $noTiket,
            'link_drive_rekom' => 'https://drive.google.com/file/d/rekom-xyz',
            'status' => 'selesai',
        ]);
    }

    public function test_update_rekomendasi_dengan_no_tiket_tidak_dikenal_404(): void
    {
        $response = $this->postJson(
            '/webhook/permohonan/rekomendasi',
            [
                'no_tiket' => 'TKT-TIDAKADA0000',
                'link_drive_rekom' => 'https://drive.google.com/file/d/xyz',
            ],
            $this->headers()
        );

        $response->assertStatus(404)->assertJson(['success' => false]);
    }
}
