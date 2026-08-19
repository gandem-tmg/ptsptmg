<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">
            {{ __('Statistik Permohonan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mb-6 overflow-hidden rounded-[28px] bg-gradient-to-r from-sky-600 via-cyan-500 to-emerald-500 p-5 text-white shadow-xl shadow-sky-200 sm:p-7">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-100">Ringkasan</p>
                    <h3 class="mt-2 text-2xl font-bold">Statistik Permohonan</h3>
                </div>
                <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3 backdrop-blur-sm">
                    <p class="text-sm text-sky-50">Periode</p>
                    <p class="text-base font-semibold">Bulan ini</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="soft-card p-5 sm:p-6">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Jumlah permohonan</h3>
                        <p class="text-sm text-slate-500">Ikhtisar performa pelayanan PTSP</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                        <div class="flex items-center justify-between">
                            <div class="rounded-xl bg-blue-100 p-3 text-blue-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="rounded-full bg-white px-2 py-1 text-xs font-medium text-slate-500">Live</span>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">Total Permohonan</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ $totalPermohonan }}</p>
                    </div>

                    <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-4">
                        <div class="flex items-center justify-between">
                            <div class="rounded-xl bg-yellow-100 p-3 text-yellow-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="rounded-full bg-white px-2 py-1 text-xs font-medium text-slate-500">Live</span>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">Diajukan</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ $permohonanDiajukan }}</p>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                        <div class="flex items-center justify-between">
                            <div class="rounded-xl bg-emerald-100 p-3 text-emerald-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="rounded-full bg-white px-2 py-1 text-xs font-medium text-slate-500">Live</span>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">Selesai</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ $permohonanSelesai }}</p>
                    </div>

                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                        <div class="flex items-center justify-between">
                            <div class="rounded-xl bg-red-100 p-3 text-red-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <span class="rounded-full bg-white px-2 py-1 text-xs font-medium text-slate-500">Live</span>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">Ditolak</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ $permohonanDitolak }}</p>
                    </div>
                </div>
            </div>

            <div class="soft-card p-5 sm:p-6">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Tabel statistik detail</h3>
                        <p class="text-sm text-slate-500">Distribusi status dan persentase permohonan</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Kategori</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Total</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Persentase</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Trend</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            <tr>
                                <td class="px-5 py-4 text-sm font-medium text-slate-900">Total Permohonan</td>
                                <td class="px-5 py-4 text-sm text-slate-600">{{ $totalPermohonan }}</td>
                                <td class="px-5 py-4 text-sm text-slate-600">100%</td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-800">+12% bulan ini</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 text-sm font-medium text-slate-900">Diajukan</td>
                                <td class="px-5 py-4 text-sm text-slate-600">{{ $permohonanDiajukan }}</td>
                                <td class="px-5 py-4 text-sm text-slate-600">
                                    @if($totalPermohonan > 0)
                                        {{ number_format(($permohonanDiajukan / $totalPermohonan) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-800">Dalam proses</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 text-sm font-medium text-slate-900">Selesai</td>
                                <td class="px-5 py-4 text-sm text-slate-600">{{ $permohonanSelesai }}</td>
                                <td class="px-5 py-4 text-sm text-slate-600">
                                    @if($totalPermohonan > 0)
                                        {{ number_format(($permohonanSelesai / $totalPermohonan) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-800">+8% bulan ini</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 text-sm font-medium text-slate-900">Ditolak</td>
                                <td class="px-5 py-4 text-sm text-slate-600">{{ $permohonanDitolak }}</td>
                                <td class="px-5 py-4 text-sm text-slate-600">
                                    @if($totalPermohonan > 0)
                                        {{ number_format(($permohonanDitolak / $totalPermohonan) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-800">-3% bulan ini</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 text-sm font-medium text-slate-900">Total Layanan</td>
                                <td class="px-5 py-4 text-sm text-slate-600">{{ $totalLayanan }}</td>
                                <td class="px-5 py-4 text-sm text-slate-600">-</td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800">Stabil</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 text-sm font-medium text-slate-900">Total Users</td>
                                <td class="px-5 py-4 text-sm text-slate-600">{{ $totalUsers }}</td>
                                <td class="px-5 py-4 text-sm text-slate-600">-</td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-800">+5% bulan ini</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="soft-card p-5 sm:p-6">
                <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Pilih jenis grafik</h3>
                        <p class="text-sm text-slate-500">Visualisasi data sesuai kebutuhan monitoring</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($chartOptions as $key => $label)
                            <a href="{{ route('statistics.index', ['chart' => $key]) }}"
                               class="rounded-xl px-4 py-2 text-sm font-medium transition duration-200 {{ $chartType === $key ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-slate-900">{{ $chartTitle }}</h4>
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
</x-app-layout>
