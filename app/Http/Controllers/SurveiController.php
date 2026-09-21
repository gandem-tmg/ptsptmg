<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;

/**
 * NB: fitur pengisian SKM secara internal SUDAH DIMATIKAN (per
 * koordinasi dengan tim lain, per 2026 — SKM sekarang cuma diisi lewat
 * satu sistem resmi di luar aplikasi ini, supaya datanya tidak dobel).
 * Controller ini sengaja dipertahankan (bukan dihapus) supaya:
 * - route lama yang mungkin sudah ter-bookmark/di-share orang tidak
 *   404, tapi otomatis diarahkan ke link SKM eksternal;
 * - pengecekan kelayakan (permohonan memang milik pemohon yang login,
 *   sudah 'selesai', dan belum pernah disurvei) tetap jalan sebelum
 *   pemohon diarahkan keluar.
 * Lihat config/skm.php untuk link tujuannya.
 */
class SurveiController extends Controller
{
    // ==========================================================
    // Survei umum (publik)
    // ==========================================================

    /**
     * Pengisian SKM internal (survei umum) sudah dimatikan — sekarang
     * semua orang yang mau isi survei umum diarahkan ke link SKM resmi
     * (satu pintu, supaya tidak dobel dengan sistem lain).
     */
    public function formUmum()
    {
        return redirect()->away(config('skm.external_url'));
    }

    /**
     * Endpoint submit lama dinonaktifkan juga (bukan cuma form-nya) —
     * kalau ada request lama yang masih nge-POST ke sini, tetap
     * diarahkan keluar, bukan disimpan ke database internal lagi.
     */
    public function submitUmum()
    {
        return redirect()->away(config('skm.external_url'));
    }

    // ==========================================================
    // Survei per-tiket (publik, tanpa login — khusus permohonan offline/
    // walk-in yang dibuatkan petugas loket, jadi tidak punya akun untuk
    // dapat notifikasi survei di dashboard seperti pemohon online biasa).
    // Diakses dari halaman lacak tiket (guest/show_ticket) begitu status
    // permohonan 'selesai'. Validasi kepemilikan cukup lewat kecocokan
    // no_tiket — level keamanannya sama dengan halaman lacak status itu
    // sendiri, yang memang sudah publik.
    // ==========================================================

    /**
     * Pengisian SKM per-tiket (walk-in) sudah dimatikan — tetap dicek
     * dulu tiketnya sah & layak diisi (status selesai, belum pernah
     * disurvei), baru diarahkan ke link SKM eksternal.
     */
    public function formPerTiket(string $no_tiket)
    {
        $this->pastikanBolehMengisiViaTiket($no_tiket);

        return redirect()->away(config('skm.external_url'));
    }

    public function submitPerTiket(string $no_tiket)
    {
        return redirect()->away(config('skm.external_url'));
    }

    private function pastikanBolehMengisiViaTiket(string $no_tiket): Permohonan
    {
        $permohonan = Permohonan::where('no_tiket', $no_tiket)->with('layanan')->firstOrFail();

        abort_unless($permohonan->status === 'selesai', 403, 'Survei baru bisa diisi setelah permohonan selesai.');
        abort_if($permohonan->surveiRespon()->exists(), 403, 'Survei untuk permohonan ini sudah pernah diisi.');

        return $permohonan;
    }

    // ==========================================================
    // Survei per-layanan (pemohon, sekali per permohonan selesai)
    // ==========================================================

    /**
     * Halaman "Survei Kepuasan" untuk pemohon: daftar layanan yang sudah
     * selesai tapi SKM-nya belum diisi, plus riwayat yang sudah diisi.
     */
    public function daftarPemohon()
    {
        $user = auth()->user();

        $belumDiisi = Permohonan::where('user_id', $user->id)
            ->selesaiBelumSurvei()
            ->with('layanan')
            ->latest('tanggal_pengajuan')
            ->get();

        $sudahDiisi = Permohonan::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->whereHas('surveiRespon')
            ->with('layanan', 'surveiRespon')
            ->latest('tanggal_pengajuan')
            ->take(10)
            ->get();

        return view('pemohon.survei.index', compact('belumDiisi', 'sudahDiisi'));
    }

    /**
     * Pengisian SKM per-layanan (pemohon) sudah dimatikan — begitu
     * permohonan pemohon sudah 'selesai' dan belum pernah disurvei,
     * mereka diarahkan ke link SKM eksternal, bukan form internal lagi.
     */
    public function formPerLayanan(Permohonan $permohonan)
    {
        $this->pastikanBolehMengisi($permohonan);

        return redirect()->away(config('skm.external_url'));
    }

    public function submitPerLayanan(Permohonan $permohonan)
    {
        return redirect()->away(config('skm.external_url'));
    }

    private function pastikanBolehMengisi(Permohonan $permohonan): void
    {
        if ($permohonan->user_id !== auth()->id()) {
            abort(403);
        }

        if ($permohonan->status !== 'selesai') {
            abort(403, 'Survei baru bisa diisi setelah permohonan selesai.');
        }

        if ($permohonan->surveiRespon()->exists()) {
            abort(403, 'Survei untuk permohonan ini sudah pernah diisi.');
        }
    }
}
