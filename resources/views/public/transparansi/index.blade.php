<x-public-layout>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

        <!-- ============ Hero ============ -->
        <div class="mb-8 overflow-hidden rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-700 via-emerald-600 to-teal-600 p-6 text-white shadow-lg shadow-emerald-900/10 sm:p-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-50 ring-1 ring-white/20">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        Keterbukaan Informasi Publik
                    </span>
                    <h1 class="mt-3 text-2xl font-bold leading-tight sm:text-3xl">Transparansi Kinerja Pelayanan Publik</h1>
                    <p class="mt-2 text-sm leading-relaxed text-emerald-50/90">
                        Data agregat kinerja layanan Pelayanan Terpadu Satu Pintu (PTSP) Kantor Kementerian Agama
                        Kabupaten Temanggung, disajikan terbuka untuk masyarakat sebagai bentuk akuntabilitas.
                        Halaman ini hanya menampilkan angka ringkasan — <strong>tidak ada nama, NIK, atau data
                        pribadi pemohon</strong> yang ditampilkan.
                    </p>
                </div>
                <div class="rounded-2xl bg-white/10 px-4 py-3 text-right ring-1 ring-white/20">
                    <p class="text-[11px] uppercase tracking-wide text-emerald-50/80">Data per</p>
                    <p class="text-sm font-semibold">{{ now()->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </div>

        <!-- ============ KPI Cards ============ -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            <div class="soft-card p-5">
                <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <p class="text-xs text-slate-500">Total Permohonan</p>
                <p class="mt-0.5 text-2xl font-bold text-slate-900">{{ number_format($totalKeseluruhan, 0, ',', '.') }}</p>
                <p class="mt-0.5 text-[11px] text-slate-400">Sejak sistem berjalan</p>
            </div>

            <div class="soft-card p-5">
                <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                </div>
                <p class="text-xs text-slate-500">Permohonan Bulan Ini</p>
                <p class="mt-0.5 text-2xl font-bold text-slate-900">{{ number_format($totalBulanIni, 0, ',', '.') }}</p>
                <p class="mt-0.5 text-[11px] text-slate-400">{{ now()->translatedFormat('F Y') }}</p>
            </div>

            <div class="soft-card p-5">
                <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-xs text-slate-500">Selesai Bulan Ini</p>
                <p class="mt-0.5 text-2xl font-bold text-emerald-600">{{ number_format($totalSelesaiBulanIni, 0, ',', '.') }}</p>
                <p class="mt-0.5 text-[11px] text-slate-400">dari {{ number_format($totalBulanIni, 0, ',', '.') }} pengajuan</p>
            </div>

            <div class="soft-card p-5">
                <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18a48.11 48.11 0 01-12.756 0c-1.085-.144-1.872-1.086-1.872-2.18v-4.25M3.75 9V8.25a2.25 2.25 0 012.25-2.25h.75V4.5A2.25 2.25 0 019 2.25h6a2.25 2.25 0 012.25 2.25v1.5h.75a2.25 2.25 0 012.25 2.25V9m-16.5 0h16.5m-16.5 0a2.25 2.25 0 01-1.5-2.122M20.25 9a2.25 2.25 0 001.5-2.122M9 12.75h6" /></svg>
                </div>
                <p class="text-xs text-slate-500">Layanan Tersedia</p>
                <p class="mt-0.5 text-2xl font-bold text-slate-900">{{ $totalLayanan }}</p>
                <p class="mt-0.5 text-[11px] text-slate-400">Jenis layanan aktif</p>
            </div>

            <!-- Tingkat Penyelesaian — kartu gauge, col-span-2 di mobile biar proporsional -->
            <div class="soft-card col-span-2 flex items-center gap-4 p-5 lg:col-span-1">
                <div class="relative h-16 w-16 shrink-0">
                    <canvas id="gaugePenyelesaian" width="64" height="64"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-sm font-bold text-slate-900">{{ $tingkatPenyelesaian }}%</span>
                    </div>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-slate-500">Tingkat Penyelesaian</p>
                    <p class="mt-0.5 text-[11px] leading-snug text-slate-400">Dari permohonan yang sudah final</p>
                </div>
            </div>
        </div>

        <!-- ============ Tren & Status ============ -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-5">
            <div class="soft-card p-5 sm:p-6 lg:col-span-3">
                <h3 class="mb-1 text-lg font-semibold text-slate-900">Tren Permohonan</h3>
                <p class="mb-4 text-sm text-slate-500">Jumlah permohonan masuk, 6 bulan terakhir</p>
                <div class="mx-auto max-w-md">
                    <canvas id="chartTrenPublik" height="200"></canvas>
                </div>
            </div>

            <div class="soft-card p-5 sm:p-6 lg:col-span-2">
                <h3 class="mb-1 text-lg font-semibold text-slate-900">Status Permohonan</h3>
                <p class="mb-4 text-sm text-slate-500">Dari {{ number_format($totalKeseluruhan, 0, ',', '.') }} permohonan sejak awal</p>
                <div class="mx-auto max-w-[220px]">
                    <canvas id="chartStatusPublik" height="220"></canvas>
                </div>
            </div>
        </div>

        <!-- ============ Layanan Terpopuler & Jalur Pengajuan ============ -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-5">
            <div class="soft-card p-5 sm:p-6 lg:col-span-3">
                <h3 class="mb-1 text-lg font-semibold text-slate-900">Layanan Paling Diminati</h3>
                <p class="mb-4 text-sm text-slate-500">Enam layanan dengan jumlah permohonan terbanyak</p>
                @if($layananTerpopuler->isEmpty())
                    <p class="text-sm text-slate-400">Belum ada data yang cukup untuk ditampilkan.</p>
                @else
                    <div class="mx-auto">
                        <canvas id="chartLayananTerpopuler" height="220"></canvas>
                    </div>
                @endif
            </div>

            <div class="soft-card p-5 sm:p-6 lg:col-span-2">
                <h3 class="mb-1 text-lg font-semibold text-slate-900">Jalur Pengajuan</h3>
                <p class="mb-4 text-sm text-slate-500">Datang langsung ke loket vs. lewat akun online</p>
                <div class="mx-auto max-w-[220px]">
                    <canvas id="chartSumberPublik" height="220"></canvas>
                </div>
            </div>
        </div>

        <!-- ============ Rata-rata waktu penyelesaian ============ -->
        <div class="mt-6 soft-card p-5 sm:p-6">
            <h3 class="mb-1 text-lg font-semibold text-slate-900">Rata-rata Waktu Penyelesaian per Layanan</h3>
            <p class="mb-5 text-sm text-slate-500">Dihitung dari tanggal pengajuan sampai layanan dinyatakan selesai (hari)</p>
            @if($rataRataPerLayanan->isEmpty())
                <p class="text-sm text-slate-400">Belum ada data yang cukup untuk ditampilkan.</p>
            @else
                @php $maxHari = $rataRataPerLayanan->max('rata_rata_hari') ?: 1; @endphp
                <div class="space-y-4">
                    @foreach($rataRataPerLayanan as $row)
                        <div>
                            <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                <span class="min-w-0 truncate font-medium text-slate-800">{{ $row->nama_layanan }}</span>
                                <span class="shrink-0 font-semibold text-slate-600">{{ $row->rata_rata_hari }} hari</span>
                            </div>
                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500"
                                     style="width: {{ max(4, round(($row->rata_rata_hari / $maxHari) * 100)) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- ============ SKM CTA ============ -->
        <div class="mt-6 soft-card overflow-hidden p-0">
            <div class="flex flex-wrap items-center justify-between gap-4 bg-gradient-to-r from-emerald-50 to-teal-50 p-5 sm:p-6">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Survei Kepuasan Masyarakat</h3>
                        <p class="text-sm text-slate-500">Pengisian &amp; hasil SKM sekarang dikelola lewat satu sistem resmi, supaya datanya tidak tercecer di beberapa tempat.</p>
                    </div>
                </div>
                <a href="{{ config('skm.external_url') }}" target="_blank" rel="noopener" class="shrink-0 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-emerald-700">Isi Survei SKM</a>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            Data bersifat agregat dan diperbarui otomatis mengikuti aktivitas layanan terkini. Halaman ini merupakan
            bagian dari komitmen keterbukaan informasi publik PTSP Kantor Kementerian Agama Kabupaten Temanggung.
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const palette = {
                emerald: '#10b981',
                blue: '#3b82f6',
                amber: '#f59e0b',
                rose: '#f43f5e',
                violet: '#8b5cf6',
                slate: '#cbd5e1',
                teal: '#14b8a6',
            };

            // --- Gauge tingkat penyelesaian ---
            const persen = {{ $tingkatPenyelesaian }};
            new Chart(document.getElementById('gaugePenyelesaian').getContext('2d'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [persen, Math.max(0, 100 - persen)],
                        backgroundColor: [palette.emerald, '#e2e8f0'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '72%',
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                }
            });

            // --- Tren bulanan ---
            const tren = @json($tren);
            new Chart(document.getElementById('chartTrenPublik').getContext('2d'), {
                type: 'line',
                data: {
                    labels: tren.labels,
                    datasets: [{ label: 'Jumlah Permohonan', data: tren.data, borderColor: palette.emerald, backgroundColor: 'rgba(16, 185, 129, 0.15)', fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: palette.emerald }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1.6,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                }
            });

            // --- Status permohonan (donut) ---
            const statusDistribusi = @json($statusDistribusi);
            new Chart(document.getElementById('chartStatusPublik').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusDistribusi.labels,
                    datasets: [{
                        data: statusDistribusi.data,
                        backgroundColor: [palette.emerald, palette.blue, palette.rose, palette.slate],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1,
                    cutout: '65%',
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } } },
                }
            });

            // --- Layanan terpopuler (bar horizontal) ---
            const layananTerpopulerEl = document.getElementById('chartLayananTerpopuler');
            if (layananTerpopulerEl) {
                const layananTerpopuler = @json($layananTerpopuler);
                new Chart(layananTerpopulerEl.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: layananTerpopuler.map(r => r.nama_layanan),
                        datasets: [{
                            label: 'Jumlah Permohonan',
                            data: layananTerpopuler.map(r => r.jumlah),
                            backgroundColor: palette.violet,
                            borderRadius: 6,
                            maxBarThickness: 26,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 1.6,
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } },
                    }
                });
            }

            // --- Jalur pengajuan (donut) ---
            const sumberDistribusi = @json($sumberDistribusi);
            new Chart(document.getElementById('chartSumberPublik').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: sumberDistribusi.labels,
                    datasets: [{
                        data: sumberDistribusi.data,
                        backgroundColor: [palette.amber, palette.teal],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1,
                    cutout: '65%',
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } } },
                }
            });
        });
    </script>
</x-public-layout>
