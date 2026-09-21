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
                <a href="{{ route('statistics.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/15 px-4 py-2.5 text-sm font-semibold backdrop-blur-sm hover:bg-white/25">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                    Export CSV
                </a>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Filter -->
            <div class="soft-card p-5 sm:p-6">
                <form method="GET" action="{{ route('statistics.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 {{ $isSeksiScoped ? 'lg:grid-cols-4' : 'lg:grid-cols-5' }}">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Bulan</label>
                        <input type="month" name="bulan" value="{{ $filters['bulan'] }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ $filters['dari'] }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ $filters['sampai'] }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Layanan</label>
                        <select name="layanan_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                            <option value="">Semua Layanan</option>
                            @foreach($layanans as $layanan)
                                <option value="{{ $layanan->id }}" @selected((string) $filters['layanan_id'] === (string) $layanan->id)>{{ $layanan->nama_layanan }}</option>
                            @endforeach
                        </select>
                    </div>
                    @unless($isSeksiScoped)
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Seksi</label>
                        <select name="seksi_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                            <option value="">Semua Seksi</option>
                            @foreach($seksis as $seksi)
                                <option value="{{ $seksi->id }}" @selected((string) $filters['seksi_id'] === (string) $seksi->id)>{{ $seksi->nama_seksi }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endunless
                    <div class="sm:col-span-2 {{ $isSeksiScoped ? 'lg:col-span-4' : 'lg:col-span-5' }} flex items-center gap-2">
                        <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Terapkan Filter</button>
                        @if($filters['bulan'] || $filters['dari'] || $filters['sampai'] || $filters['layanan_id'] || $filters['seksi_id'])
                            <a href="{{ route('statistics.index') }}" class="text-xs font-semibold text-slate-400 hover:text-slate-600">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Kartu ringkasan -->
            <div class="soft-card p-5 sm:p-6">
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-slate-900">Jumlah permohonan</h3>
                    <p class="text-sm text-slate-500">Sesuai filter yang berlaku di atas</p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-[12px] font-medium text-slate-500">Total Permohonan</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900">{{ $totalPermohonan }}</p>
                    </div>
                    <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-[12px] font-medium text-slate-500">Diajukan</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900">{{ $permohonanDiajukan }}</p>
                    </div>
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-[12px] font-medium text-slate-500">Selesai</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900">{{ $permohonanSelesai }}</p>
                    </div>
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-[12px] font-medium text-slate-500">Ditolak</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900">{{ $permohonanDitolak }}</p>
                    </div>
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-[12px] font-medium text-slate-500">Dikembalikan</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900">{{ $permohonanDikembalikan }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-[12px] font-medium text-slate-500">Dibatalkan Pemohon</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900">{{ $permohonanDibatalkan }}</p>
                    </div>
                    <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-[12px] font-medium text-slate-500">{{ $isSeksiScoped ? 'Layanan di Seksi Saya' : 'Total Layanan' }}</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900">{{ $totalLayanan }}</p>
                    </div>
                    @if($isAdmin)
                        <div class="rounded-xl border border-purple-200 bg-purple-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                            <p class="text-[12px] font-medium text-slate-500">Total Users</p>
                            <p class="mt-1.5 text-2xl font-bold text-slate-900">{{ $totalUsers }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rata-rata waktu proses per layanan -->
            <div class="soft-card p-5 sm:p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">Rata-rata Waktu Proses per Layanan</h3>
                    <p class="text-sm text-slate-500">Dari tanggal pengajuan sampai status selesai (hari), hanya yang sudah selesai</p>
                </div>
                @if($rataRataPerLayanan->isEmpty())
                    <p class="text-sm text-slate-400">Belum ada permohonan yang selesai pada rentang filter ini.</p>
                @else
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Layanan</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Jumlah Selesai</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Rata-rata Hari</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach($rataRataPerLayanan as $row)
                                    <tr>
                                        <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $row->nama_layanan }}</td>
                                        <td class="px-5 py-3 text-sm text-slate-600">{{ $row->jumlah_selesai }}</td>
                                        <td class="px-5 py-3 text-sm text-slate-600">{{ $row->rata_rata_hari }} hari</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Layanan terpopuler -->
                <div class="soft-card p-5 sm:p-6">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Layanan Terpopuler</h3>
                    @if($layananTerpopuler->isEmpty())
                        <p class="text-sm text-slate-400">Belum ada data.</p>
                    @else
                        <ol class="space-y-2">
                            @foreach($layananTerpopuler as $row)
                                <li class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-2.5 text-sm">
                                    <span class="font-medium text-slate-800">{{ $loop->iteration }}. {{ $row->nama_layanan }}</span>
                                    <span class="font-semibold text-emerald-600">{{ $row->jumlah }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>

                <!-- Beban aktif per seksi -->
                <div class="soft-card p-5 sm:p-6">
                    <h3 class="mb-1 text-lg font-semibold text-slate-900">{{ $isSeksiScoped ? 'Beban Aktif Seksi Saya' : 'Beban Aktif per Seksi' }}</h3>
                    <p class="mb-4 text-sm text-slate-500">Permohonan yang sedang ditangani saat ini (tidak terpengaruh filter tanggal)</p>
                    @if($bebanAktifPerSeksi->isEmpty())
                        <p class="text-sm text-slate-400">{{ $isSeksiScoped ? 'Tidak ada permohonan yang sedang aktif di seksi Anda.' : 'Tidak ada permohonan yang sedang aktif di seksi manapun.' }}</p>
                    @else
                        <ol class="space-y-2">
                            @foreach($bebanAktifPerSeksi as $row)
                                <li class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-2.5 text-sm">
                                    <span class="font-medium text-slate-800">{{ $row->nama_seksi }}</span>
                                    <span class="font-semibold text-sky-600">{{ $row->jumlah }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>

            <!-- Grafik -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="soft-card p-5 sm:p-6">
                    <h4 class="mb-3 text-base font-semibold text-slate-900">Tren Bulanan (6 Bulan Terakhir)</h4>
                    <canvas id="chartTren" height="220"></canvas>
                </div>
                <div class="soft-card p-5 sm:p-6">
                    <h4 class="mb-3 text-base font-semibold text-slate-900">Permohonan per Layanan</h4>
                    <canvas id="chartLayanan" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tren = @json($tren);
            new Chart(document.getElementById('chartTren').getContext('2d'), {
                type: 'line',
                data: {
                    labels: tren.labels,
                    datasets: [{ label: 'Jumlah Permohonan', data: tren.data, borderColor: '#27ae60', backgroundColor: 'rgba(39, 174, 96, 0.2)', fill: true, tension: 0.4 }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });

            new Chart(document.getElementById('chartLayanan').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($layananChartLabels),
                    datasets: [{ label: 'Jumlah Permohonan', data: @json($layananChartData), backgroundColor: '#9b59b6' }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });

        });
    </script>
</x-app-layout>
