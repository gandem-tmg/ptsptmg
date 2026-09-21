<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Permohonan;
use App\Support\StatistikPermohonan;

/**
 * Dashboard transparansi publik: siapa saja bisa lihat tanpa login.
 * SENGAJA hanya menampilkan angka agregat (total & rata-rata) — tidak ada
 * nama pemohon, NIK, atau detail permohonan siapapun di halaman ini.
 *
 * Catatan: agregat SKM (nilai IKM & rata-rata per layanan) sudah dicabut
 * dari halaman ini sejak pengisian SKM dialihkan ke sistem eksternal —
 * lihat config/skm.php.
 */
class TransparansiController extends Controller
{
    public function index()
    {
        $bulanIni = now()->startOfMonth();

        $totalBulanIni = Permohonan::whereYear('tanggal_pengajuan', $bulanIni->year)
            ->whereMonth('tanggal_pengajuan', $bulanIni->month)
            ->count();

        $totalSelesaiBulanIni = Permohonan::whereYear('tanggal_pengajuan', $bulanIni->year)
            ->whereMonth('tanggal_pengajuan', $bulanIni->month)
            ->where('status', 'selesai')
            ->count();

        $totalKeseluruhan = Permohonan::count();

        // Distribusi status disederhanakan jadi 4 kelompok yang mudah dibaca
        // publik (bukan daftar status teknis internal seperti "verifikasi_ptsp",
        // "didisposisikan", dst) — dipakai untuk diagram donut di bawah.
        $totalSelesai = Permohonan::where('status', 'selesai')->count();
        $totalDitolak = Permohonan::where('status', 'ditolak')->count();
        $totalDibatalkan = Permohonan::where('status', 'dibatalkan')->count();
        $totalSedangDiproses = Permohonan::whereIn('status', Permohonan::statusAktif())->count();

        $statusDistribusi = [
            'labels' => ['Selesai', 'Sedang Diproses', 'Ditolak', 'Dibatalkan'],
            'data' => [$totalSelesai, $totalSedangDiproses, $totalDitolak, $totalDibatalkan],
        ];

        // Tingkat penyelesaian keseluruhan — dari semua permohonan yang
        // sudah final (selesai/ditolak/dibatalkan), berapa persen yang
        // berhasil selesai. Permohonan yang masih berjalan tidak dihitung
        // supaya angkanya tidak bias turun cuma karena banyak yang masih
        // antre diproses.
        $totalFinal = $totalSelesai + $totalDitolak + $totalDibatalkan;
        $tingkatPenyelesaian = $totalFinal > 0 ? round(($totalSelesai / $totalFinal) * 100, 1) : 0;

        // Proporsi jalur pengajuan: walk-in (datang langsung ke loket) vs
        // online (lewat akun). Agregat murni, tidak membocorkan identitas.
        $totalWalkIn = Permohonan::where('sumber_pengajuan', 'walk_in')->count();
        $totalOnline = $totalKeseluruhan - $totalWalkIn;
        $sumberDistribusi = [
            'labels' => ['Datang Langsung (Loket)', 'Pengajuan Online'],
            'data' => [$totalWalkIn, $totalOnline],
        ];

        $rataRataPerLayanan = StatistikPermohonan::rataRataHariSelesaiPerLayanan(Permohonan::query());

        $layananTerpopuler = StatistikPermohonan::layananTerpopuler(Permohonan::query(), 6);

        $tren = StatistikPermohonan::trenBulanan(null, null);

        return view('public.transparansi.index', [
            'totalBulanIni' => $totalBulanIni,
            'totalSelesaiBulanIni' => $totalSelesaiBulanIni,
            'totalKeseluruhan' => $totalKeseluruhan,
            'totalLayanan' => Layanan::count(),
            'tingkatPenyelesaian' => $tingkatPenyelesaian,
            'rataRataPerLayanan' => $rataRataPerLayanan,
            'layananTerpopuler' => $layananTerpopuler,
            'tren' => $tren,
            'statusDistribusi' => $statusDistribusi,
            'sumberDistribusi' => $sumberDistribusi,
        ]);
    }
}
