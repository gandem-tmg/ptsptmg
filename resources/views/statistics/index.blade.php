<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistik Permohonan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg shadow-md border border-blue-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Permohonan</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $totalPermohonan }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 p-6 rounded-lg shadow-md border border-yellow-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Diajukan</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $permohonanDiajukan }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Selesai</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $permohonanSelesai }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-red-50 p-6 rounded-lg shadow-md border border-red-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Ditolak</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $permohonanDitolak }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Table -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Tabel Statistik Detail</h3>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Permohonan</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $totalPermohonan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">100%</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    +12% bulan ini
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Diajukan</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $permohonanDiajukan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($totalPermohonan > 0)
                                                    {{ number_format(($permohonanDiajukan / $totalPermohonan) * 100, 1) }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Dalam proses
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Selesai</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $permohonanSelesai }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($totalPermohonan > 0)
                                                    {{ number_format(($permohonanSelesai / $totalPermohonan) * 100, 1) }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    +8% bulan ini
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Ditolak</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $permohonanDitolak }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($totalPermohonan > 0)
                                                    {{ number_format(($permohonanDitolak / $totalPermohonan) * 100, 1) }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    -3% bulan ini
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Layanan</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $totalLayanan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Stabil
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Users</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $totalUsers }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    +5% bulan ini
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Selector and Display -->
                    <div class="mb-8">
                        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 lg:mb-0">Pilih Jenis Grafik</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($chartOptions as $key => $label)
                                        <a href="{{ route('statistics.index', ['chart' => $key]) }}"
                                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $chartType === $key ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                            {{ $label }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Selected Chart Display -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $chartTitle }}</h4>
                                </div>
                                <div class="w-full">
                                    <canvas id="statisticsChart" width="400" height="200"></canvas>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const ctx = document.getElementById('statisticsChart').getContext('2d');
                                    const chartData = @json($chartData);

                                    new Chart(ctx, {
                                        type: chartData.type,
                                        data: {
                                            labels: chartData.labels,
                                            datasets: chartData.datasets
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    position: 'top',
                                                },
                                                title: {
                                                    display: true,
                                                    text: '{{ $chartTitle }}'
                                                }
                                            },
                                            scales: chartData.type !== 'pie' && chartData.type !== 'doughnut' ? {
                                                y: {
                                                    beginAtZero: true,
                                                    ticks: {
                                                        stepSize: 1
                                                    }
                                                }
                                            } : {}
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
