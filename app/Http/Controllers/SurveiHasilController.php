<?php

namespace App\Http\Controllers;

use App\Models\SurveiRespon;
use App\Support\StatistikSurvei;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Halaman hasil SKM internal — admin, petugas PTSP, dan pimpinan. Beda
 * dengan dashboard transparansi publik: di sini boleh lihat daftar respon
 * individual (termasuk nama pengisi & komentar bebas) untuk keperluan
 * tindak lanjut, bukan cuma agregat.
 */
class SurveiHasilController extends Controller
{
    private const ROLE_DIIZINKAN = ['admin', 'petugas', 'pimpinan'];

    public function index(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, self::ROLE_DIIZINKAN)) {
            abort(403);
        }

        [$dari, $sampai, $filters] = $this->resolveFilters($request);

        $nilaiIkm = StatistikSurvei::nilaiIkm(null, $dari, $sampai);
        $rataRataPerPertanyaan = StatistikSurvei::rataRataPerPertanyaan(null, $dari, $sampai);
        $rataRataPerLayanan = StatistikSurvei::rataRataPerLayanan($dari, $sampai);
        $rataRataPerSeksi = StatistikSurvei::rataRataPerSeksi($dari, $sampai);
        $distribusiSkala = StatistikSurvei::distribusiSkala($dari, $sampai);

        $responQuery = SurveiRespon::with(['permohonan.layanan', 'jawaban.pertanyaan']);
        if ($dari) {
            $responQuery->whereDate('created_at', '>=', Carbon::parse($dari));
        }
        if ($sampai) {
            $responQuery->whereDate('created_at', '<=', Carbon::parse($sampai));
        }

        $responTerbaru = $responQuery
            ->latest()
            ->paginate(20)
            ->withQueryString()
            ->through(function ($respon) {
                $ratingJawaban = $respon->jawaban->whereNotNull('nilai_rating');
                $respon->rata_rata_respon = $ratingJawaban->isNotEmpty() ? round($ratingJawaban->avg('nilai_rating'), 1) : null;

                return $respon;
            });

        return view('survei.hasil', compact(
            'nilaiIkm',
            'rataRataPerPertanyaan',
            'rataRataPerLayanan',
            'rataRataPerSeksi',
            'distribusiSkala',
            'responTerbaru',
            'filters'
        ));
    }

    /**
     * Unduh hasil SKM (sesuai filter periode yang berlaku) sebagai PDF —
     * dipakai untuk laporan bulanan/triwulanan ke pimpinan tanpa harus
     * screenshot halaman.
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, self::ROLE_DIIZINKAN)) {
            abort(403);
        }

        [$dari, $sampai, $filters] = $this->resolveFilters($request);

        $nilaiIkm = StatistikSurvei::nilaiIkm(null, $dari, $sampai);
        $rataRataPerPertanyaan = StatistikSurvei::rataRataPerPertanyaan(null, $dari, $sampai);
        $rataRataPerLayanan = StatistikSurvei::rataRataPerLayanan($dari, $sampai);
        $rataRataPerSeksi = StatistikSurvei::rataRataPerSeksi($dari, $sampai);
        $distribusiSkala = StatistikSurvei::distribusiSkala($dari, $sampai);

        $periodeLabel = $filters['periode_label'];

        $pdf = Pdf::loadView('survei.hasil-pdf', compact(
            'nilaiIkm',
            'rataRataPerPertanyaan',
            'rataRataPerLayanan',
            'rataRataPerSeksi',
            'distribusiSkala',
            'periodeLabel'
        ))->setPaper('a4', 'portrait');

        $filename = 'hasil-skm-' . \Illuminate\Support\Str::slug($periodeLabel) . '-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Ubah input filter mentah (bulan / triwulan+tahun / dari+sampai) jadi
     * rentang tanggal siap pakai, plus array $filters buat isi ulang form
     * & label periode di judul laporan. Prioritas kalau lebih dari satu
     * diisi: bulan > triwulan > dari/sampai manual.
     */
    private function resolveFilters(Request $request): array
    {
        $bulan = $request->input('bulan');
        $triwulan = $request->input('triwulan');
        $tahun = $request->input('tahun');
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');
        $periodeLabel = 'Semua Periode';

        if ($bulan) {
            try {
                $awalBulan = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
                $dari = $awalBulan->toDateString();
                $sampai = $awalBulan->copy()->endOfMonth()->toDateString();
                $periodeLabel = $awalBulan->translatedFormat('F Y');
            } catch (\Exception $e) {
                // format bulan tidak valid — abaikan, jatuh balik ke opsi lain (kalau ada)
            }
        } elseif ($triwulan && $tahun) {
            try {
                $triwulanInt = (int) $triwulan;
                $tahunInt = (int) $tahun;
                $bulanAwal = ($triwulanInt - 1) * 3 + 1;
                $awal = Carbon::create($tahunInt, $bulanAwal, 1)->startOfMonth();
                $dari = $awal->toDateString();
                $sampai = $awal->copy()->addMonths(2)->endOfMonth()->toDateString();
                $periodeLabel = 'Triwulan ' . $triwulanInt . ' Tahun ' . $tahunInt;
            } catch (\Exception $e) {
                //
            }
        } elseif ($dari || $sampai) {
            $periodeLabel = ($dari ?: '...') . ' s/d ' . ($sampai ?: '...');
        }

        $filters = [
            'bulan' => $bulan,
            'triwulan' => $triwulan,
            'tahun' => $tahun,
            'dari' => $dari,
            'sampai' => $sampai,
            'periode_label' => $periodeLabel,
        ];

        return [$dari, $sampai, $filters];
    }
}
