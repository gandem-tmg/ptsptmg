<?php

namespace App\Http\Controllers;

use App\Models\LampiranHasil;
use App\Models\Permohonan;
use App\Models\SuratKeluar;
use App\Models\TemplateSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SuratController extends Controller
{
    /**
     * Petugas seksi: pilih template & lihat preview sebelum generate.
     *
     * FITUR DINONAKTIFKAN (atas permintaan): generate surat dari template
     * sengaja ditutup dulu — alur "Surat Hasil" sekarang HANYA lewat unggah
     * dokumen manual (lihat PermohonanController::selesaikanSeksi()).
     * Tombol "Generate Surat" juga sudah disembunyikan di
     * petugas_seksi/permohonan/show.blade.php, tapi endpoint ini tetap
     * dijaga di sini supaya tidak bisa diakses langsung lewat URL juga.
     * Route & kode di controller ini SENGAJA dibiarkan (bukan dihapus)
     * supaya gampang diaktifkan kembali kalau suatu saat dibutuhkan lagi.
     */
    public function create(Permohonan $permohonan)
    {
        abort(403, 'Fitur Generate Surat dari template sedang dinonaktifkan. Gunakan unggah dokumen hasil manual di halaman detail permohonan.');

        $user = auth()->user();
        if ($user->role !== 'petugas_seksi' || $permohonan->current_seksi_id !== $user->seksi_id) {
            abort(403);
        }

        $templates = TemplateSurat::where('layanan_id', $permohonan->layanan_id)
            ->where('aktif', true)
            ->get();

        return view('petugas_seksi.surat.create', compact('permohonan', 'templates'));
    }

    /**
     * Generate PDF draft dari template (belum tanda tangan).
     */
    public function store(Request $request, Permohonan $permohonan)
    {
        // Lihat catatan nonaktif di create() di atas.
        abort(403, 'Fitur Generate Surat dari template sedang dinonaktifkan. Gunakan unggah dokumen hasil manual di halaman detail permohonan.');

        $user = auth()->user();
        if ($user->role !== 'petugas_seksi' || $permohonan->current_seksi_id !== $user->seksi_id) {
            abort(403);
        }

        $request->validate([
            'template_surat_id' => 'required|exists:template_surat,id',
        ]);

        $template = TemplateSurat::findOrFail($request->template_surat_id);
        $seksi = $permohonan->currentSeksi;

        $nomorSurat = SuratKeluar::generateNomorSurat($seksi);

        $isiSurat = $template->render([
            'nama_pemohon' => $permohonan->user->name ?? $permohonan->nama,
            'nik' => $permohonan->user->nik ?? $permohonan->nik,
            'alamat_pemohon' => $permohonan->user->alamat ?? $permohonan->alamat,
            'no_tiket' => $permohonan->no_tiket,
            'nama_layanan' => $permohonan->layanan->nama_layanan,
            'nomor_surat' => $nomorSurat,
            'tanggal_surat' => now()->translatedFormat('d F Y'),
        ]);

        $kodeVerifikasi = SuratKeluar::generateKodeVerifikasi();

        $surat = SuratKeluar::create([
            'permohonan_id' => $permohonan->id,
            'template_surat_id' => $template->id,
            'nomor_surat' => $nomorSurat,
            'isi_surat' => $isiSurat,
            'file_path' => '', // diisi setelah PDF digenerate di bawah
            'metode_ttd' => 'belum_ttd',
            'kode_verifikasi' => $kodeVerifikasi,
            'dibuat_oleh' => $user->id,
        ]);

        $this->renderPdf($surat);

        return redirect()->route('seksi.surat.show', $surat)->with('success', 'Draft surat berhasil dibuat. Silakan tandatangani sebelum diserahkan ke PTSP.');
    }

    public function show(SuratKeluar $surat)
    {
        $user = auth()->user();
        $surat->load('permohonan.layanan', 'template', 'penandatangan');

        if ($user->role === 'petugas_seksi' && $surat->permohonan->current_seksi_id !== $user->seksi_id) {
            abort(403);
        }

        return view('petugas_seksi.surat.show', compact('surat'));
    }

    /**
     * Tanda tangan manual: petugas unggah hasil scan surat yang sudah
     * ditandatangani basah oleh pejabat berwenang.
     */
    public function tandatanganManual(Request $request, SuratKeluar $surat)
    {
        $user = auth()->user();
        if ($user->role !== 'petugas_seksi' || $surat->permohonan->current_seksi_id !== $user->seksi_id) {
            abort(403);
        }

        $request->validate([
            'file_scan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('file_scan')->store('surat_keluar', 'public');

        $surat->update([
            'file_path' => $path,
            'metode_ttd' => 'manual',
            'ditandatangani_oleh' => $user->id,
            'tanggal_ttd' => now(),
        ]);

        $this->generateQrVerifikasi($surat);
        $this->salinKeLampiranHasil($surat);

        return redirect()->route('seksi.surat.show', $surat)->with('success', 'Surat hasil scan tanda tangan manual berhasil diunggah.');
    }

    /**
     * "TTE ringan": bubuhkan blok tanda tangan elektronik + QR verifikasi
     * langsung ke PDF. INI BUKAN tanda tangan elektronik tersertifikasi
     * (bukan BSrE/PSrE) — hanya penanda + mekanisme verifikasi keaslian.
     * Untuk kekuatan hukum penuh, integrasikan dengan BSrE secara terpisah.
     */
    public function tandatanganTte(Request $request, SuratKeluar $surat)
    {
        $user = auth()->user();
        if ($user->role !== 'petugas_seksi' || $surat->permohonan->current_seksi_id !== $user->seksi_id) {
            abort(403);
        }

        $request->validate([
            'nama_penandatangan' => 'required|string|max:255',
            'jabatan_penandatangan' => 'required|string|max:255',
        ]);

        $surat->update([
            'metode_ttd' => 'tte',
            'ditandatangani_oleh' => $user->id,
            'tanggal_ttd' => now(),
        ]);

        $this->generateQrVerifikasi($surat);
        $this->renderPdf($surat, [
            'nama_penandatangan' => $request->nama_penandatangan,
            'jabatan_penandatangan' => $request->jabatan_penandatangan,
        ]);
        $this->salinKeLampiranHasil($surat);

        return redirect()->route('seksi.surat.show', $surat)->with('success', 'Surat berhasil ditandatangani secara elektronik.');
    }

    public function download(SuratKeluar $surat)
    {
        // Sebelumnya method ini tidak ada pengecekan otorisasi sama sekali
        // (beda dengan show() di atas) — artinya petugas_seksi dari seksi
        // MANA PUN bisa download surat resmi milik seksi lain kalau tahu/
        // tebak ID surat-nya. Disamakan dengan show().
        $user = auth()->user();
        if ($user->role === 'petugas_seksi' && $surat->permohonan->current_seksi_id !== $user->seksi_id) {
            abort(403);
        }

        $path = storage_path('app/public/' . $surat->file_path);
        if (!file_exists($path)) {
            abort(404);
        }
        // Nomor surat mengandung '/' (format B-1/Kk.13.../PENMA/IX/2026) yang
        // tidak boleh dipakai langsung sebagai nama file unduhan.
        $namaFile = str_replace(['/', '\\'], '-', $surat->nomor_surat) . '.pdf';

        return response()->download($path, $namaFile);
    }

    // ==========================================================
    // Helper internal
    // ==========================================================

    private function renderPdf(SuratKeluar $surat, ?array $ttd = null): void
    {
        // dompdf tidak bisa render <img src="file.svg">, jadi SVG QR
        // disisipkan langsung sebagai markup inline di dalam PDF.
        $qrSvgMarkup = $surat->qr_verifikasi_path
            ? Storage::disk('public')->get($surat->qr_verifikasi_path)
            : null;

        $pdf = Pdf::loadView('surat.pdf', [
            'surat' => $surat,
            'ttd' => $ttd,
            'qrSvgMarkup' => $qrSvgMarkup,
        ])->setPaper('a4', 'portrait');

        $path = 'surat_keluar/' . str_replace(['/', ' '], '-', $surat->nomor_surat) . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        $surat->update(['file_path' => $path]);
    }

    private function generateQrVerifikasi(SuratKeluar $surat): void
    {
        Storage::disk('public')->makeDirectory('qrcode_surat');

        // Sama seperti QR tiket — pakai SVG, tidak butuh ekstensi Imagick.
        $path = 'qrcode_surat/' . $surat->kode_verifikasi . '.svg';
        $url = route('verifikasi.surat.show', $surat->kode_verifikasi);

        $svg = QrCode::format('svg')->size(120)->generate($url);
        Storage::disk('public')->put($path, $svg);

        $surat->update(['qr_verifikasi_path' => $path]);
    }

    /**
     * Begitu surat sudah final (sudah ditandatangani), otomatis muncul juga
     * di tab "Dokumen Hasil" pada halaman detail permohonan yang sudah ada,
     * supaya PTSP & pimpinan tidak perlu buka halaman terpisah.
     */
    private function salinKeLampiranHasil(SuratKeluar $surat): void
    {
        LampiranHasil::create([
            'permohonan_id' => $surat->permohonan_id,
            'nama_dokumen' => $surat->nomor_surat,
            'file_path' => $surat->file_path,
            'diunggah_oleh' => $surat->ditandatangani_oleh,
            'tanggal_unggah' => now(),
        ]);
    }
}
