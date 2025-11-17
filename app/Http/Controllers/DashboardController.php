<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Permohonan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'pemohon') {
            $totalLayanan = Layanan::count();
            $totalPermohonan = Permohonan::where('user_id', $user->id)->count();
            $permohonanDiajukan = Permohonan::where('user_id', $user->id)->where('status', 'Diajukan')->count();
            $permohonanSelesai = Permohonan::where('user_id', $user->id)->where('status', 'Selesai')->count();

            return view('dashboard', compact(
                'totalLayanan',
                'totalPermohonan',
                'permohonanDiajukan',
                'permohonanSelesai'
            ));
        } else {
            $totalLayanan = Layanan::count();
            $totalPermohonan = Permohonan::count();
            $permohonanDiajukan = Permohonan::where('status', 'Diajukan')->count();
            $permohonanSelesai = Permohonan::where('status', 'Selesai')->count();
            $permohonanDitolak = Permohonan::where('status', 'Ditolak')->count();

            return view('dashboard', compact(
                'totalLayanan',
                'totalPermohonan',
                'permohonanDiajukan',
                'permohonanSelesai',
                'permohonanDitolak'
            ));
        }
    }

    private function createDailyChart()
    {
        $chart = new Chart();
        $chart->title('Permohonan Harian (7 Hari Terakhir)');
        $chart->labels(['7 Hari Lalu', '6 Hari Lalu', '5 Hari Lalu', '4 Hari Lalu', '3 Hari Lalu', 'Kemarin', 'Hari Ini']);

        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $count = Permohonan::whereDate('created_at', $date)->count();
            $data[] = $count;
        }

        $chart->dataset('Jumlah Permohonan', 'line', $data)
              ->color('#3498db')
              ->backgroundColor('rgba(52, 152, 219, 0.2)')
              ->fill(true);

        return $chart;
    }

    private function createWeeklyChart()
    {
        $chart = new Chart();
        $chart->title('Permohonan Mingguan (4 Minggu Terakhir)');
        $chart->labels(['4 Minggu Lalu', '3 Minggu Lalu', '2 Minggu Lalu', 'Minggu Lalu', 'Minggu Ini']);

        $data = [];
        for ($i = 4; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();
            $count = Permohonan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            $data[] = $count;
        }

        $chart->dataset('Jumlah Permohonan', 'bar', $data)
              ->color('#e74c3c')
              ->backgroundColor('#e74c3c');

        return $chart;
    }

    private function createMonthlyChart()
    {
        $chart = new Chart();
        $chart->title('Permohonan Bulanan (6 Bulan Terakhir)');

        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $count = Permohonan::whereYear('created_at', $date->year)
                              ->whereMonth('created_at', $date->month)
                              ->count();
            $data[] = $count;
        }

        $chart->labels($labels);
        $chart->dataset('Jumlah Permohonan', 'line', $data)
              ->color('#27ae60')
              ->backgroundColor('rgba(39, 174, 96, 0.2)')
              ->fill(true);

        return $chart;
    }

    private function createUnitKerjaChart()
    {
        $chart = new Chart();
        $chart->title('Permohonan per Unit Kerja');

        $unitKerjaData = Permohonan::selectRaw('unit_kerja, COUNT(*) as count')
                                  ->whereNotNull('unit_kerja')
                                  ->groupBy('unit_kerja')
                                  ->orderBy('count', 'desc')
                                  ->get();

        $labels = $unitKerjaData->pluck('unit_kerja')->toArray();
        $data = $unitKerjaData->pluck('count')->toArray();

        $chart->labels($labels);
        $chart->dataset('Jumlah Permohonan', 'doughnut', $data)
              ->color(['#3498db', '#e74c3c', '#27ae60', '#f39c12', '#9b59b6', '#1abc9c', '#34495e'])
              ->backgroundColor(['#3498db', '#e74c3c', '#27ae60', '#f39c12', '#9b59b6', '#1abc9c', '#34495e']);

        return $chart;
    }
}
