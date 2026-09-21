<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Permohonan;
use App\Models\Seksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Alur cepat untuk Petugas PTSP membuatkan tiket bagi pemohon yang datang
 * langsung ke loket. SENGAJA jauh lebih ringkas dari alur online/guest lama:
 * tidak ada upload file/KTP, tidak ada NIK — karena verifikasi kelengkapan
 * dokumen dilakukan langsung secara fisik oleh petugas di loket, cukup
 * dikonfirmasi lewat checklist. Fungsinya murni untuk menerbitkan nomor
 * tiket supaya permohonan tetap tercatat & bisa dilacak di sistem.
 */
class PermohonanOfflineController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role, ['petugas', 'admin'])) {
            abort(403);
        }

        $layanans = Layanan::with('seksi', 'persyaratan')->orderBy('nama_layanan')->get();

        $seksis = Seksi::orderBy('nama_seksi')->get()->map(function ($seksi) use ($layanans) {
            $seksi->layananList = $layanans->where('seksi_id', $seksi->id)->values();
            return $seksi;
        });

        return view('petugas.permohonan_offline.index', compact('seksis'));
    }

    public function create(Layanan $layanan)
    {
        if (!in_array(auth()->user()->role, ['petugas', 'admin'])) {
            abort(403);
        }

        $layanan->load('seksi', 'persyaratan');

        return view('petugas.permohonan_offline.create', compact('layanan'));
    }

    /**
     * Form untuk permohonan yang layanannya TIDAK ADA di daftar layanan
     * resmi seksi ini, tapi masih tupoksi Kemenag — jadi tetap perlu
     * dicatat & dilacak, hanya saja nama/deskripsi layanannya diisi
     * manual oleh petugas (bukan dipilih dari katalog `layanan`).
     */
    public function manualCreate(Seksi $seksi)
    {
        if (!in_array(auth()->user()->role, ['petugas', 'admin'])) {
            abort(403);
        }

        return view('petugas.permohonan_offline.manual_create', compact('seksi'));
    }

    public function manualStore(Request $request)
    {
        if (!in_array(auth()->user()->role, ['petugas', 'admin'])) {
            abort(403);
        }

        $request->validate([
            'seksi_id' => 'required|exists:seksi,id',
            'nama_layanan_manual' => 'required|string|max:255',
            'deskripsi_layanan_manual' => 'nullable|string',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'nullable|string|max:20',
            'kelengkapan_manual' => 'required|in:lengkap,belum_lengkap',
        ]);

        $permohonan = Permohonan::create([
            'user_id' => null,
            'layanan_id' => null,
            'is_manual' => true,
            'manual_seksi_id' => $request->seksi_id,
            'nama_layanan_manual' => $request->nama_layanan_manual,
            'deskripsi_layanan_manual' => $request->deskripsi_layanan_manual,
            'kelengkapan_manual' => $request->kelengkapan_manual,
            'tanggal_pengajuan' => now(),
            'status' => 'diajukan',
            'sumber_pengajuan' => 'walk_in',
            'no_tiket' => Permohonan::generateNoTiket(),
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        $catatanKelengkapan = $request->kelengkapan_manual === 'lengkap'
            ? 'Kelengkapan berkas dinyatakan lengkap oleh petugas loket.'
            : 'Kelengkapan berkas BELUM lengkap, dicatat tetap oleh petugas loket untuk ditindaklanjuti.';

        $this->generateQrCode($permohonan);
        $permohonan->catatPerubahanStatus(
            'diajukan',
            auth()->id(),
            'Permohonan layanan di luar katalog (input manual) oleh petugas loket PTSP. ' . $catatanKelengkapan
        );

        $permohonan->load('manualSeksi');
        $this->generateBuktiPdf($permohonan);

        return redirect()->route('petugas.permohonan.show', $permohonan)
            ->with('success', 'Tiket berhasil dibuat: ' . $permohonan->no_tiket);
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['petugas', 'admin'])) {
            abort(403);
        }

        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'nullable|string|max:20',
            'persyaratan_checked' => 'array',
        ]);

        $layanan = Layanan::with('persyaratan')->findOrFail($request->layanan_id);
        $checked = collect($request->input('persyaratan_checked', []))->map(fn ($v) => (int) $v);

        // Semua persyaratan WAJIB harus tercentang sebelum tiket bisa terbit.
        $belumLengkap = $layanan->persyaratan->where('wajib', true)
            ->reject(fn ($p) => $checked->contains($p->id));

        if ($belumLengkap->isNotEmpty()) {
            return back()->withInput()->withErrors([
                'persyaratan' => 'Persyaratan wajib belum lengkap: ' . $belumLengkap->pluck('nama_persyaratan')->implode(', '),
            ]);
        }

        $permohonan = Permohonan::create([
            'user_id' => null,
            'layanan_id' => $layanan->id,
            'tanggal_pengajuan' => now(),
            'status' => 'diajukan',
            'sumber_pengajuan' => 'walk_in',
            'no_tiket' => Permohonan::generateNoTiket(),
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        $this->generateQrCode($permohonan);
        $permohonan->catatPerubahanStatus(
            'diajukan',
            auth()->id(),
            'Dibuatkan oleh petugas loket PTSP untuk pemohon yang datang langsung. Kelengkapan persyaratan sudah dicek fisik di loket.'
        );

        $permohonan->load('layanan');
        $this->generateBuktiPdf($permohonan);

        return redirect()->route('petugas.permohonan.show', $permohonan)
            ->with('success', 'Tiket berhasil dibuat: ' . $permohonan->no_tiket);
    }

    private function generateQrCode(Permohonan $permohonan): void
    {
        Storage::disk('public')->makeDirectory('qrcode');
        $path = 'qrcode/' . $permohonan->no_tiket . '.svg';
        $url = route('guest.trackTicket', $permohonan->no_tiket);
        $svg = QrCode::format('svg')->size(300)->generate($url);
        Storage::disk('public')->put($path, $svg);
        $permohonan->update(['qr_code_path' => $path]);
    }

    private function generateBuktiPdf(Permohonan $permohonan): void
    {
        $pdf = Pdf::loadView('pemohon.permohonan.pdf', compact('permohonan'))->setPaper([0, 0, 252, 432], 'portrait');
        Storage::disk('public')->put('permohonan_pdf/' . $permohonan->no_tiket . '.pdf', $pdf->output());
    }
}
