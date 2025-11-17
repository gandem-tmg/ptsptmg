<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $chartType = $request->get('chart', 'monthly'); // Default to monthly chart

        // Calculate statistics
        $totalPermohonan = Permohonan::count();
        $permohonanDiajukan = Permohonan::where('status', 'diajukan')->count();
        $permohonanSelesai = Permohonan::where('status', 'selesai')->count();
        $permohonanDitolak = Permohonan::where('status', 'ditolak')->count();
        $totalLayanan = \App\Models\Layanan::count();
        $totalUsers = \App\Models\User::count();

        // Prepare chart data based on type
        $chartData = $this->getChartData($chartType);

        $chartTitle = $this->getChartTitle($chartType);

        $chartOptions = [
            'monthly' => 'Permohonan Bulanan',
            'unit_kerja' => 'Permohonan per Unit Kerja',
            'layanan' => 'Permohonan per Layanan',
            'status' => 'Status Permohonan'
        ];

        return view('statistics.index', compact(
            'totalPermohonan',
            'permohonanDiajukan',
            'permohonanSelesai',
            'permohonanDitolak',
            'totalLayanan',
            'totalUsers',
            'chartData',
            'chartTitle',
            'chartType',
            'chartOptions'
        ));
    }

    private function getChartTitle($chartType)
    {
        $titles = [
            'monthly' => 'Permohonan Bulanan (6 Bulan Terakhir)',
            'unit_kerja' => 'Permohonan per Unit Kerja',
            'layanan' => 'Permohonan per Layanan',
            'status' => 'Status Permohonan'
        ];

        return $titles[$chartType] ?? 'Permohonan Bulanan (6 Bulan Terakhir)';
    }

    private function getChartData($chartType)
    {
        switch ($chartType) {
            case 'monthly':
                return $this->getMonthlyChartData();
            case 'unit_kerja':
                return $this->getUnitKerjaChartData();
            case 'layanan':
                return $this->getLayananChartData();
            case 'status':
                return $this->getStatusChartData();
            default:
                return $this->getMonthlyChartData();
        }
    }

    private function getMonthlyChartData()
    {
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

        return [
            'type' => 'line',
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $data,
                    'borderColor' => '#27ae60',
                    'backgroundColor' => 'rgba(39, 174, 96, 0.2)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];
    }

    private function getUnitKerjaChartData()
    {
        $unitKerjaData = Permohonan::selectRaw('unit_kerja, COUNT(*) as count')
                                  ->whereNotNull('unit_kerja')
                                  ->groupBy('unit_kerja')
                                  ->orderBy('count', 'desc')
                                  ->get();

        $labels = $unitKerjaData->pluck('unit_kerja')->toArray();
        $data = $unitKerjaData->pluck('count')->toArray();

        $colors = ['#3498db', '#e74c3c', '#27ae60', '#f39c12', '#9b59b6', '#1abc9c', '#34495e', '#e67e22', '#95a5a6', '#f1c40f'];

        return [
            'type' => 'doughnut',
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    private function getLayananChartData()
    {
        $layananData = Permohonan::selectRaw('layanan.nama_layanan, COUNT(*) as count')
                                ->join('layanan', 'permohonan.layanan_id', '=', 'layanan.id')
                                ->groupBy('layanan.nama_layanan')
                                ->orderBy('count', 'desc')
                                ->get();

        $labels = $layananData->pluck('nama_layanan')->toArray();
        $data = $layananData->pluck('count')->toArray();

        return [
            'type' => 'bar',
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $data,
                    'backgroundColor' => '#9b59b6',
                    'borderColor' => '#8e44ad',
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    private function getStatusChartData()
    {
        $statusData = [
            'diajukan' => Permohonan::where('status', 'diajukan')->count(),
            'selesai' => Permohonan::where('status', 'selesai')->count(),
            'ditolak' => Permohonan::where('status', 'ditolak')->count(),
        ];

        $labels = ['Diajukan', 'Selesai', 'Ditolak'];
        $data = array_values($statusData);

        return [
            'type' => 'pie',
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $data,
                    'backgroundColor' => ['#f39c12', '#27ae60', '#e74c3c'],
                    'borderColor' => ['#e67e22', '#229954', '#c0392b'],
                    'borderWidth' => 1
                ]
            ]
        ];
    }
}
