<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Permohonan;
use App\Models\Seksi;
use App\Models\User;
use App\Support\StatistikPermohonan;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Statistik internal (admin, petugas PTSP, pimpinan, & petugas seksi).
 * Admin dan petugas PTSP melihat lintas seksi; pimpinan juga lintas seksi
 * (read-only, sama seperti monitoring). Khusus petugas_seksi, datanya
 * DIPAKSA terkunci ke seksi milik akun yang login (seksi_id tidak bisa
 * dioper-oper lewat query string) — supaya satu seksi tidak bisa mengintip
 * beban kerja atau statistik seksi lain. Cuma kartu "Total Users" yang
 * dikhususkan untuk admin (urusan manajemen akun bukan ranah role lain).
 */
class StatisticsController extends Controller
{
    private const ROLE_DIIZINKAN = ['admin', 'petugas', 'pimpinan', 'petugas_seksi'];

    public function index(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, self::ROLE_DIIZINKAN)) {
            abort(403);
        }

        $isSeksiScoped = $user->role === 'petugas_seksi';

        [$dari, $sampai, $layananId, $seksiId] = $this->resolveFilters($request);

        if ($isSeksiScoped) {
            $seksiId = $user->seksi_id;
        }

        $baseQuery = Permohonan::query();
        StatistikPermohonan::applyFilter($baseQuery, $dari, $sampai, $layananId, $seksiId);

        $totalPermohonan = (clone $baseQuery)->count();
        $permohonanDiajukan = (clone $baseQuery)->where('status', 'diajukan')->count();
        $permohonanSelesai = (clone $baseQuery)->where('status', 'selesai')->count();
        $permohonanDitolak = (clone $baseQuery)->where('status', 'ditolak')->count();
        $permohonanDikembalikan = (clone $baseQuery)->where('status', 'dikembalikan')->count();
        $permohonanDibatalkan = (clone $baseQuery)->where('status', 'dibatalkan')->count();

        $layananChartData = (clone $baseQuery)
            ->with('layanan')
            ->get()
            ->filter(fn ($p) => $p->layanan)
            ->groupBy('layanan.nama_layanan')
            ->map->count()
            ->sortByDesc(fn ($count) => $count);

        $layananQuery = $isSeksiScoped
            ? Layanan::where('seksi_id', $user->seksi_id)
            : Layanan::query();

        return view('statistics.index', [
            'isAdmin' => $user->role === 'admin',
            'isSeksiScoped' => $isSeksiScoped,
            'totalPermohonan' => $totalPermohonan,
            'permohonanDiajukan' => $permohonanDiajukan,
            'permohonanSelesai' => $permohonanSelesai,
            'permohonanDitolak' => $permohonanDitolak,
            'permohonanDikembalikan' => $permohonanDikembalikan,
            'permohonanDibatalkan' => $permohonanDibatalkan,
            'totalLayanan' => (clone $layananQuery)->count(),
            'totalUsers' => $user->role === 'admin' ? User::count() : null,
            'rataRataPerLayanan' => StatistikPermohonan::rataRataHariSelesaiPerLayanan(clone $baseQuery),
            'layananTerpopuler' => StatistikPermohonan::layananTerpopuler(clone $baseQuery),
            'bebanAktifPerSeksi' => StatistikPermohonan::bebanAktifPerSeksi($isSeksiScoped ? $user->seksi_id : null),
            'tren' => StatistikPermohonan::trenBulanan($layananId, $seksiId),
            'layananChartLabels' => $layananChartData->keys()->values(),
            'layananChartData' => $layananChartData->values(),
            'layanans' => (clone $layananQuery)->orderBy('nama_layanan')->get(),
            'seksis' => $isSeksiScoped ? collect() : Seksi::orderBy('nama_seksi')->get(),
            'filters' => [
                'bulan' => $request->input('bulan'),
                'dari' => $dari,
                'sampai' => $sampai,
                'layanan_id' => $layananId,
                'seksi_id' => $seksiId,
            ],
        ]);
    }

    /**
     * Export ringkasan per layanan (sesuai filter yang berlaku) sebagai
     * CSV — dipakai untuk laporan bulanan ke pimpinan tanpa harus screenshot
     * halaman statistik.
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, self::ROLE_DIIZINKAN)) {
            abort(403);
        }

        [$dari, $sampai, $layananId, $seksiId] = $this->resolveFilters($request);

        if ($user->role === 'petugas_seksi') {
            $seksiId = $user->seksi_id;
        }

        $baseQuery = Permohonan::query();
        StatistikPermohonan::applyFilter($baseQuery, $dari, $sampai, $layananId, $seksiId);

        $jumlahPerLayanan = (clone $baseQuery)
            ->with('layanan')
            ->get()
            ->filter(fn ($p) => $p->layanan)
            ->groupBy('layanan_id')
            ->map(fn ($group) => (object) [
                'nama_layanan' => $group->first()->layanan->nama_layanan,
                'jumlah' => $group->count(),
            ])
            ->sortBy('nama_layanan');

        $rataRataByNama = StatistikPermohonan::rataRataHariSelesaiPerLayanan(clone $baseQuery)->keyBy('nama_layanan');

        $periodeLabel = $dari && $sampai ? "{$dari}_sampai_{$sampai}" : 'semua-periode';
        $filename = 'statistik-permohonan-' . $periodeLabel . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($jumlahPerLayanan, $rataRataByNama) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Layanan', 'Jumlah Permohonan', 'Jumlah Selesai', 'Rata-rata Hari Proses']);

            foreach ($jumlahPerLayanan as $row) {
                $rr = $rataRataByNama->get($row->nama_layanan);
                fputcsv($out, [
                    $row->nama_layanan,
                    $row->jumlah,
                    $rr->jumlah_selesai ?? 0,
                    $rr->rata_rata_hari ?? '-',
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /**
     * Ubah input filter mentah (bulan / dari / sampai / layanan_id /
     * seksi_id) jadi bentuk siap pakai. Kalau 'bulan' (format Y-m) diisi,
     * itu menang dan menggantikan dari/sampai manual.
     */
    private function resolveFilters(Request $request): array
    {
        $bulan = $request->input('bulan');
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        if ($bulan) {
            try {
                $awalBulan = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
                $dari = $awalBulan->toDateString();
                $sampai = $awalBulan->copy()->endOfMonth()->toDateString();
            } catch (\Exception $e) {
                // format bulan tidak valid — abaikan, jatuh balik ke dari/sampai manual (kalau ada)
            }
        }

        $layananId = $request->integer('layanan_id') ?: null;
        $seksiId = $request->integer('seksi_id') ?: null;

        return [$dari, $sampai, $layananId, $seksiId];
    }
}
