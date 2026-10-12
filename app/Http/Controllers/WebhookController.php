<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Permohonan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class WebhookController extends Controller
{
    /**
     * Tipe layanan yang dikenali dari aplikasi Layanan Madrasah
     * (harus sinkron dengan App\Support\LayananWorkflow::map() di sana).
     */
    private const TIPE_LAYANAN_VALID = [
        'rekomendasi', 'rekomendasi_kamad', 'pindah_sekolah',
        'input_siswa', 'reaktivasi', 'reset_verval', 'salah_tingkat', 'perubahan_ijop',
    ];

    /**
     * FASE 1 — Menerima request tiket baru dari Layanan Madrasah.
     *
     * POST /webhook/permohonan/tiket
     * Header: X-Webhook-Secret
     * Body  : id_layanan, tipe_layanan, nama_madrasah, nsm, link_drive_berkas
     *
     * PERBAIKAN (sesi lanjutan, 28 Agt 2026): field `tipe_layanan` WAJIB
     * divalidasi & disimpan sebagai `tipe_layanan_asal`. Sebelumnya
     * integrasi hanya menyimpan `id_layanan_asal` tanpa tipe, padahal
     * Layanan Madrasah sekarang mengirim 6 tipe layanan berbeda yang
     * masing-masing punya penomoran id sendiri mulai dari 1 — tanpa kolom
     * ini, id_layanan=5 dari tipe 'rekomendasi' tidak bisa dibedakan dari
     * id_layanan=5 milik tipe 'input_siswa', sehingga pengecekan idempotensi
     * di bawah bisa salah mengembalikan no_tiket milik pengajuan lain yang
     * sama sekali tidak berhubungan. Lihat juga migration
     * 2026_08_28_000002_add_tipe_layanan_asal_to_permohonan_table.
     */
    public function receiveTiket(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id_layanan'        => ['required'],
                'tipe_layanan'      => ['required', 'string', 'in:' . implode(',', self::TIPE_LAYANAN_VALID)],
                'nama_madrasah'     => ['required', 'string', 'max:255'],
                'nsm'               => ['required', 'string', 'max:20'],
                'link_drive_berkas' => ['nullable', 'url', 'max:2048'],
            ]);
        } catch (ValidationException $e) {
            Log::warning('[Webhook][Tiket] Payload tidak valid.', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Payload tidak valid.',
                'errors'  => $e->errors(),
            ], 422);
        }

        try {
            $permohonan = DB::transaction(function () use ($validated) {
                // Idempotensi: kalau id_layanan_asal ini sudah pernah dikirim
                // sebelumnya, kembalikan tiket yang sama (hindari duplikat
                // saat Job di-retry dan request pertama sebenarnya sukses
                // tapi response-nya gagal diterima LM).
                $existing = Permohonan::where('id_layanan_asal', (string) $validated['id_layanan'])
                    ->where('tipe_layanan_asal', $validated['tipe_layanan'])
                    ->where('sumber', 'layanan_madrasah')
                    ->first();

                if ($existing) {
                    return $existing;
                }

                try {
                    return Permohonan::create([
                        'no_tiket'          => Permohonan::generateNoTiket(),
                        'layanan_id'        => $this->resolveLayananId($validated['tipe_layanan']),
                        'tanggal_pengajuan' => now()->toDateString(),
                        'status'            => 'diajukan',
                        'sumber'            => 'layanan_madrasah',
                        'id_layanan_asal'   => (string) $validated['id_layanan'],
                        'tipe_layanan_asal' => $validated['tipe_layanan'],
                        'nsm'               => $validated['nsm'],
                        'nama_madrasah'     => $validated['nama_madrasah'],
                        'nama'              => $validated['nama_madrasah'],
                        'link_drive_berkas' => $validated['link_drive_berkas'] ?? null,
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // Race condition: dua request Fase 1 untuk id_layanan+tipe
                    // yang sama nyaris bersamaan lolos pengecekan `$existing`
                    // di atas sebelum salah satunya sempat commit. Unique index
                    // `permohonan_sumber_tipe_id_layanan_asal_unique`
                    // (migration 2026_08_28_000002) akan menolak insert kedua
                    // dengan SQLSTATE 23000. Di sini kita anggap itu bukan error
                    // fatal — cukup ambil baris yang berhasil dibuat request lain.
                    if ((int) $e->getCode() === 23000) {
                        $row = Permohonan::where('id_layanan_asal', (string) $validated['id_layanan'])
                            ->where('tipe_layanan_asal', $validated['tipe_layanan'])
                            ->where('sumber', 'layanan_madrasah')
                            ->first();
                        if ($row) {
                            return $row;
                        }
                    }
                    throw $e;
                }
            });

            Log::info('[Webhook][Tiket] Tiket berhasil dibuat.', [
                'no_tiket' => $permohonan->no_tiket,
                'id_layanan_asal' => $validated['id_layanan'],
            ]);

            return response()->json([
                'success'     => true,
                'nomor_tiket' => $permohonan->no_tiket,
                'message'     => 'Tiket berhasil dibuat.',
            ], 201);
        } catch (\Throwable $e) {
            Log::error('[Webhook][Tiket] Gagal menyimpan permohonan.', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses permintaan tiket.',
            ], 500);
        }
    }

    /**
     * FASE 2 — Menerima update link rekomendasi dari Layanan Madrasah.
     *
     * POST /webhook/permohonan/rekomendasi
     * Header: X-Webhook-Secret
     * Body  : no_tiket, link_drive_rekom
     */
    public function updateRekomendasi(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'no_tiket'         => ['required', 'string'],
                'link_drive_rekom' => ['required', 'url', 'max:2048'],
            ]);
        } catch (ValidationException $e) {
            Log::warning('[Webhook][Rekom] Payload tidak valid.', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Payload tidak valid.',
                'errors'  => $e->errors(),
            ], 422);
        }

        $permohonan = Permohonan::where('no_tiket', $validated['no_tiket'])->first();

        if (!$permohonan) {
            Log::warning('[Webhook][Rekom] no_tiket tidak ditemukan.', ['no_tiket' => $validated['no_tiket']]);

            return response()->json([
                'success' => false,
                'message' => 'Nomor tiket tidak ditemukan.',
            ], 404);
        }

        try {
            $permohonan->update([
                'link_drive_rekom' => $validated['link_drive_rekom'],
                'status'           => 'selesai', // "Rekomendasi Terbit"
            ]);

            // NOTE: tidak menyalin ke tabel `layanan` — UI harus membaca dari
            // tabel `permohonan` yang menyimpan `link_drive_rekom` secara
            // semantik (Fase 2). Jika masih ada kode lama yang mengandalkan
            // kolom `layanan.link_drive_rekom`, hapus/ubahnya.
            Log::info('[Webhook][Rekom] Rekomendasi berhasil diperbarui.', ['no_tiket' => $permohonan->no_tiket]);

            return response()->json([
                'success' => true,
                'message' => 'Rekomendasi berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            Log::error('[Webhook][Rekom] Gagal memperbarui permohonan.', [
                'no_tiket' => $validated['no_tiket'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui rekomendasi.',
            ], 500);
        }
    }

    /**
     * Menentukan FK `layanan_id` (tabel `layanan` milik PTSP sendiri) untuk
     * permohonan yang masuk lewat webhook, berdasarkan tipe layanan asal.
     *
     * BELUM DIVERIFIKASI KE DATA PRODUCTION (lihat HANDOFF_NOTES.md): semua
     * tipe untuk saat ini di-map ke satu id yang sama
     * (LAYANAN_MADRASAH_DEFAULT_LAYANAN_ID, default 26 = "Surat
     * Rekomendasi"), karena dari dump SQL yang dilampirkan tidak ditemukan
     * baris `layanan` terpisah untuk "Rekomendasi Kepala Madrasah",
     * "Pindah Sekolah", "Input Siswa EMIS", "Reaktivasi Siswa", atau
     * "Reset QR Verval". Kalau di production PTSP ternyata memang ingin
     * mencatatnya sebagai layanan terpisah (mis. untuk laporan statistik
     * per-layanan), tambahkan mapping eksplisit lewat env, contoh:
     *
     *   LAYANAN_MADRASAH_LAYANAN_ID_REKOMENDASI_KAMAD=45
     *
     * lalu tambahkan barisnya di config/services.php
     * ('layanan_madrasah.layanan_id_map') dan panggil di sini.
     */
    private function resolveLayananId(string $tipeLayanan): int
    {
        $map = (array) config('services.layanan_madrasah.layanan_id_map', []);

        // Nilai 0 (atau kosong) di map berarti "belum di-mapping secara
        // eksplisit" -> pakai default_layanan_id. PENTING: gunakan !empty(),
        // BUKAN operator ?? -- karena env() yang belum diisi menghasilkan int
        // 0 (bukan null), dan `$map[$tipeLayanan] ?? $default` TIDAK akan
        // pernah fallback ke default selama key-nya ada dengan nilai 0.
        $candidate = !empty($map[$tipeLayanan])
            ? (int) $map[$tipeLayanan]
            : (int) config('services.layanan_madrasah.default_layanan_id');

        if (Layanan::whereKey($candidate)->exists()) {
            return $candidate;
        }

        $fallback = Layanan::query()->orderBy('id')->value('id');
        if ($fallback) {
            return (int) $fallback;
        }

        throw new \RuntimeException(sprintf(
            'Tidak ada baris layanan PTSP yang valid untuk tipe layanan "%s". Pastikan tabel layanan memiliki ID %d atau baris fallback yang tersedia.',
            $tipeLayanan,
            $candidate
        ));
    }

    /**
     * Gunakan generator nomor tiket yang dipakai PTSP untuk semua alur,
     * termasuk webhook. Format yang benar: YYMMDD-###.
     */
    private function generateNomorTiket(): string
    {
        return Permohonan::generateNoTiket();
    }
}
