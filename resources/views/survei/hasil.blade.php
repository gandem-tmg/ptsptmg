<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Hasil Survei Kepuasan Masyarakat (SKM)</h2>
    </x-slot>

    <div class="space-y-6 py-2">

        <div class="mb-1 overflow-hidden rounded-[28px] bg-gradient-to-r from-sky-600 via-cyan-500 to-emerald-500 p-5 text-white shadow-xl shadow-sky-200 sm:p-7">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-100">Periode</p>
                    <h3 class="mt-2 text-2xl font-bold">{{ $filters['periode_label'] }}</h3>
                </div>
                <a href="{{ route('survei.hasil.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/15 px-4 py-2.5 text-sm font-semibold backdrop-blur-sm hover:bg-white/25">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                    Unduh Hasil SKM (PDF)
                </a>
            </div>
        </div>

        <!-- Filter periode -->
        <div class="soft-card p-5 sm:p-6">
            <form method="GET" action="{{ route('survei.hasil') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Per Bulan</label>
                    <input type="month" name="bulan" value="{{ $filters['bulan'] }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Triwulan</label>
                    <div class="flex gap-2">
                        <select name="triwulan" class="w-1/2 rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                            <option value="">Pilih</option>
                            @foreach([1 => 'I (Jan-Mar)', 2 => 'II (Apr-Jun)', 3 => 'III (Jul-Sep)', 4 => 'IV (Okt-Des)'] as $val => $label)
                                <option value="{{ $val }}" @selected((string) $filters['triwulan'] === (string) $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <select name="tahun" class="w-1/2 rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                            <option value="">Tahun</option>
                            @foreach(range(now()->year, now()->year - 4) as $tahunOpsi)
                                <option value="{{ $tahunOpsi }}" @selected((string) $filters['tahun'] === (string) $tahunOpsi)>{{ $tahunOpsi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ $filters['dari'] }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ $filters['sampai'] }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                </div>
                <div class="sm:col-span-2 lg:col-span-4 flex items-center gap-2">
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Terapkan Filter</button>
                    @if($filters['bulan'] || $filters['triwulan'] || $filters['dari'] || $filters['sampai'])
                        <a href="{{ route('survei.hasil') }}" class="text-xs font-semibold text-slate-400 hover:text-slate-600">Reset</a>
                    @endif
                </div>
            </form>
            <p class="mt-2 text-xs text-slate-400">Isi salah satu saja — kalau Bulan diisi, itu yang dipakai; kalau tidak, baru Triwulan; kalau tidak ada juga, baru rentang tanggal manual.</p>
        </div>

        <!-- Ringkasan: Nilai IKM + donut distribusi -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
            <div class="soft-card flex flex-col justify-center p-6 lg:col-span-2">
                <p class="text-sm text-slate-500">Nilai IKM</p>
                @if($nilaiIkm)
                    <p class="mt-1 text-5xl font-bold tracking-tight text-slate-900">{{ $nilaiIkm['nilai'] }}</p>
                    <span class="mt-2 inline-flex w-fit items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold
                        @if($nilaiIkm['kategori'] === 'Sangat Baik') bg-emerald-100 text-emerald-700
                        @elseif($nilaiIkm['kategori'] === 'Baik') bg-sky-100 text-sky-700
                        @elseif($nilaiIkm['kategori'] === 'Kurang Baik') bg-amber-100 text-amber-700
                        @else bg-rose-100 text-rose-700 @endif">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        {{ $nilaiIkm['kategori'] }}
                    </span>
                    <p class="mt-3 text-xs text-slate-400">Skala 0–100, dari {{ $nilaiIkm['jumlah_jawaban'] }} jawaban · mengikuti Permenpan RB No. 14/2017</p>
                @else
                    <p class="mt-2 text-lg text-slate-400">Belum ada data</p>
                @endif
            </div>

            <div class="soft-card p-5 lg:col-span-3">
                <p class="mb-2 text-sm font-semibold text-slate-700">Sebaran Penilaian</p>
                @if(array_sum($distribusiSkala) === 0)
                    <p class="text-sm text-slate-400">Belum ada jawaban skala masuk.</p>
                @else
                    <div class="flex flex-col items-center gap-5 sm:flex-row">
                        <div class="relative h-40 w-40 shrink-0">
                            <canvas id="skalaDonutChart"></canvas>
                            <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-xl font-bold text-slate-900">{{ array_sum($distribusiSkala) }}</span>
                                <span class="text-[11px] text-slate-400">respon</span>
                            </div>
                        </div>
                        <div class="w-full space-y-2">
                            @php
                                $total = array_sum($distribusiSkala);
                                $warna = ['Tidak Baik' => 'bg-rose-400', 'Kurang Baik' => 'bg-amber-400', 'Baik' => 'bg-sky-400', 'Sangat Baik' => 'bg-emerald-500'];
                            @endphp
                            @foreach($distribusiSkala as $label => $jumlah)
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $warna[$label] }}"></span>
                                    <span class="w-24 shrink-0 text-slate-600">{{ $label }}</span>
                                    <span class="font-semibold text-slate-900">{{ $jumlah }}</span>
                                    <span class="text-slate-400">({{ $total > 0 ? round($jumlah / $total * 100) : 0 }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rata-rata per pertanyaan: bar visual, bukan angka mentah -->
        <div class="soft-card p-5 sm:p-6">
            <p class="mb-3 text-sm font-semibold text-slate-700">Rata-rata per Pertanyaan</p>
            @if($rataRataPerPertanyaan->isEmpty())
                <p class="text-sm text-slate-400">Belum ada pertanyaan tipe skala 1-4.</p>
            @else
                <div class="space-y-3">
                    @foreach($rataRataPerPertanyaan as $row)
                        <div>
                            <div class="mb-1 flex items-center justify-between gap-2 text-xs">
                                <span class="truncate text-slate-600">{{ $row->pertanyaan }}</span>
                                <span class="shrink-0 font-semibold text-slate-900">{{ $row->rata_rata ?? '-' }}<span class="font-normal text-slate-400">/4</span></span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-emerald-500" style="width: {{ $row->rata_rata ? round($row->rata_rata / 4 * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="soft-card p-5 sm:p-6">
                <h3 class="mb-3 text-sm font-semibold text-slate-700">Rata-rata per Layanan</h3>
                @if($rataRataPerLayanan->isEmpty())
                    <p class="text-sm text-slate-400">Belum ada data survei per-layanan.</p>
                @else
                    <div class="space-y-3">
                        @foreach($rataRataPerLayanan as $row)
                            <div>
                                <div class="mb-1 flex items-center justify-between gap-2 text-xs">
                                    <span class="truncate font-medium text-slate-700">{{ $row->nama_layanan }}</span>
                                    <span class="shrink-0 font-semibold text-emerald-600">{{ $row->rata_rata }}/4</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-emerald-500" style="width: {{ round($row->rata_rata / 4 * 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="soft-card p-5 sm:p-6">
                <h3 class="mb-3 text-sm font-semibold text-slate-700">Rata-rata per Seksi</h3>
                @if($rataRataPerSeksi->isEmpty())
                    <p class="text-sm text-slate-400">Belum ada data survei per-layanan.</p>
                @else
                    <div class="space-y-3">
                        @foreach($rataRataPerSeksi as $row)
                            <div>
                                <div class="mb-1 flex items-center justify-between gap-2 text-xs">
                                    <span class="truncate font-medium text-slate-700">{{ $row->nama_seksi }}</span>
                                    <span class="shrink-0 font-semibold text-sky-600">{{ $row->rata_rata }}/4</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-sky-500" style="width: {{ round($row->rata_rata / 4 * 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Respon terbaru: ringkas, detail Q&A cuma muncul kalau di-klik -->
        <div class="soft-card p-5 sm:p-6">
            <h3 class="mb-4 text-sm font-semibold text-slate-700">Respon Terbaru</h3>
            @if($responTerbaru->isEmpty())
                <p class="text-sm text-slate-400">Belum ada respon survei masuk.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($responTerbaru as $respon)
                        <div x-data="{ open: false }" class="py-3 first:pt-0 last:pb-0">
                            <button type="button" @click="open = !open" class="flex w-full items-center gap-3 text-left">
                                @php
                                    $skor = $respon->rata_rata_respon;
                                    $warnaSkor = match(true) {
                                        $skor === null => 'bg-slate-100 text-slate-400',
                                        $skor >= 3.5 => 'bg-emerald-100 text-emerald-700',
                                        $skor >= 2.5 => 'bg-sky-100 text-sky-700',
                                        $skor >= 1.5 => 'bg-amber-100 text-amber-700',
                                        default => 'bg-rose-100 text-rose-700',
                                    };
                                @endphp
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold {{ $warnaSkor }}">
                                    {{ $skor ?? '-' }}
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-medium text-slate-800">
                                        {{ $respon->nama_pengisi ?: 'Anonim' }}
                                        <span class="font-normal text-slate-400">&middot; {{ $respon->jenis_survei === 'per_layanan' ? ($respon->permohonan->layanan->nama_layanan ?? 'Layanan tidak diketahui') : 'Survei Umum' }}</span>
                                    </span>
                                    <span class="block text-xs text-slate-400">{{ $respon->created_at->format('d M Y, H:i') }}</span>
                                </span>
                                <svg class="h-4 w-4 shrink-0 text-slate-400 transition" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </button>

                            <div x-show="open" x-cloak x-transition class="mt-3 space-y-3" style="margin-left: 3.25rem;">
                                @if($respon->jenis_kelamin || $respon->usia_rentang || $respon->pendidikan_terakhir || $respon->pekerjaan_utama)
                                    <div class="flex flex-wrap gap-1.5">
                                        @if($respon->jenis_kelamin)
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-600">{{ $respon->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                        @endif
                                        @if($respon->usia_rentang)
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-600">{{ $respon->usia_rentang }}</span>
                                        @endif
                                        @if($respon->pendidikan_terakhir)
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-600">{{ $respon->pendidikan_terakhir }}</span>
                                        @endif
                                        @if($respon->pekerjaan_utama)
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-600">{{ $respon->pekerjaan_utama }}</span>
                                        @endif
                                    </div>
                                @endif
                                <div class="space-y-1.5 rounded-xl bg-slate-50 p-3 text-sm sm:grid sm:grid-cols-2 sm:gap-1.5 sm:space-y-0">
                                    @foreach($respon->jawaban as $jawaban)
                                        <p class="text-slate-600">
                                            <span class="font-medium text-slate-800">{{ $jawaban->pertanyaan->teks_pertanyaan ?? '-' }}:</span>
                                            {{ $jawaban->nilai_rating ?? $jawaban->pilihan_jawaban ?? $jawaban->jawaban_teks ?? '-' }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $responTerbaru->links() }}</div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('skalaDonutChart');
            if (canvas && window.Chart) {
                new Chart(canvas, {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_keys($distribusiSkala)),
                        datasets: [{
                            data: @json(array_values($distribusiSkala)),
                            backgroundColor: ['#fb7185', '#fbbf24', '#38bdf8', '#10b981'],
                            borderWidth: 0,
                        }],
                    },
                    options: {
                        cutout: '72%',
                        plugins: { legend: { display: false } },
                        responsive: true,
                        maintainAspectRatio: false,
                    },
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
