<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;

class VerifikasiSuratController extends Controller
{
    /**
     * Halaman publik hasil scan QR di surat — sengaja hanya menampilkan
     * info minimal (bukan seluruh isi surat/data pribadi) untuk konfirmasi
     * keaslian, bukan untuk membaca ulang isi suratnya.
     */
    public function show(string $kode)
    {
        $surat = SuratKeluar::where('kode_verifikasi', $kode)
            ->with('permohonan.layanan', 'penandatangan')
            ->first();

        if (!$surat || $surat->metode_ttd === 'belum_ttd') {
            return view('public.verifikasi.not_found');
        }

        return view('public.verifikasi.show', compact('surat'));
    }
}
