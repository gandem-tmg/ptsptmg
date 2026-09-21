<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">
            Dasbor
        </h2>
    </x-slot>

    @php
        $iconPaths = [
            'inbox' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a1 1 0 00-.9.55l-.7 1.4a1 1 0 01-.9.55h-3a1 1 0 01-.9-.55l-.7-1.4a1 1 0 00-.9-.55H4',
            'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'check' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'exclamation' => 'M12 9v3.75m0 3.75h.007v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'arrow-path' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99',
            'briefcase' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        ];
        $colorMap = [
            'emerald' => 'bg-emerald-50 text-emerald-600',
            'amber' => 'bg-amber-50 text-amber-600',
            'sky' => 'bg-sky-50 text-sky-600',
            'purple' => 'bg-purple-50 text-purple-600',
            'rose' => 'bg-rose-50 text-rose-600',
        ];
    @endphp

    <div class="space-y-6 py-2">
        @if(Auth::user()->role === 'pemohon' && !Auth::user()->no_hp)
        <div class="flex items-center justify-between gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <span>Nomor HP Anda belum diisi — akan dicetak di bukti pengajuan supaya petugas mudah menghubungi Anda.</span>
            <a href="{{ route('profile.edit') }}" class="shrink-0 font-semibold text-amber-900 hover:underline">Lengkapi &rarr;</a>
        </div>
        @endif

        <!-- Hero sapaan -->
        <div class="overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-sky-500 p-4 text-white shadow-md shadow-emerald-100 sm:p-5">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-100">Selamat datang kembali</p>
                    <h3 class="mt-2 text-2xl font-bold">{{ Auth::user()->name }}</h3>
                </div>
                <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3 backdrop-blur-sm">
                    <p class="text-xs text-emerald-50">Peran</p>
                    <p class="text-base font-semibold capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                </div>
            </div>
        </div>

        <!-- Kartu statistik -->
        @php
            $colsClass = match(count($cards)) {
                1 => 'lg:grid-cols-1',
                2 => 'lg:grid-cols-2',
                3 => 'lg:grid-cols-3',
                4 => 'lg:grid-cols-4',
                default => 'lg:grid-cols-5',
            };
        @endphp
        <div class="grid grid-cols-2 gap-3 sm:gap-4 {{ $colsClass }}">
            @foreach($cards as $stat)
            @php $cardTag = !empty($stat['href']) ? 'a' : 'div'; @endphp
            <{{ $cardTag }}
                @if(!empty($stat['href'])) href="{{ $stat['href'] }}" @endif
                class="rounded-xl border border-slate-200 bg-white p-4 transition-all duration-200 {{ !empty($stat['href']) ? 'hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md' : '' }}"
            >
                <div class="inline-flex rounded-lg p-2 {{ $colorMap[$stat['color']] ?? $colorMap['emerald'] }}">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths[$stat['icon']] ?? $iconPaths['inbox'] }}" />
                    </svg>
                </div>
                <p class="mt-2.5 text-2xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                <p class="text-[12px] text-slate-500">{{ $stat['label'] }}</p>
            </{{ $cardTag }}>
            @endforeach
        </div>

        <!-- Breakdown per seksi (kalau ada) -->
        @isset($seksiBreakdown)
        @php
            $seksiAktif = $seksiBreakdown->where('permohonan_aktif_count', '>', 0)->sortByDesc('permohonan_aktif_count');
            $seksiKosong = $seksiBreakdown->where('permohonan_aktif_count', 0);
            $maxCount = $seksiAktif->max('permohonan_aktif_count') ?: 1;
        @endphp
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
            <h3 class="mb-4 text-sm font-semibold text-slate-900">Beban per Seksi (sedang ditangani)</h3>

            @if($seksiAktif->isEmpty())
                <div class="rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">
                    Tidak ada permohonan yang sedang ditangani seksi manapun saat ini.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($seksiAktif as $seksi)
                    <div class="flex items-center gap-3">
                        <span class="w-40 shrink-0 truncate text-sm font-medium text-slate-700 sm:w-56">{{ $seksi->nama_seksi }}</span>
                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ max(8, ($seksi->permohonan_aktif_count / $maxCount) * 100) }}%"></div>
                        </div>
                        <span class="w-6 shrink-0 text-right text-sm font-semibold text-slate-900">{{ $seksi->permohonan_aktif_count }}</span>
                    </div>
                    @endforeach
                </div>
            @endif

            @if($seksiKosong->isNotEmpty())
            <details class="mt-4 group">
                <summary class="cursor-pointer text-xs font-medium text-slate-400 hover:text-slate-600">
                    + {{ $seksiKosong->count() }} seksi lain tanpa permohonan aktif
                </summary>
                <div class="mt-2 flex flex-wrap gap-1.5">
                    @foreach($seksiKosong as $seksi)
                        <span class="rounded-full bg-slate-50 px-2.5 py-1 text-xs text-slate-400">{{ $seksi->nama_seksi }}</span>
                    @endforeach
                </div>
            </details>
            @endif
        </div>
        @endisset

        <!-- Daftar permohonan (card list, bukan tabel lebar) -->
        @if($listTitle)
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">{{ $listTitle }}</h3>
                @if($routePrefix)
                <a href="{{ route($routePrefix . '.permohonan.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                    Lihat semua &rarr;
                </a>
                @endif
            </div>

            @if($listItems->isEmpty())
                <div class="rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">
                    {{ $emptyText ?? 'Belum ada data.' }}
                </div>
            @else
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($listItems as $permohonan)
                        <x-permohonan-card :permohonan="$permohonan" :route-prefix="$routePrefix" :show-seksi="$showSeksiOnCard ?? true" />
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        @if(Auth::user()->role === 'pemohon')
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 sm:flex sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-semibold text-emerald-900">Butuh layanan lain?</h3>
                <p class="text-xs text-emerald-700">Lihat semua layanan yang tersedia dan ajukan langsung.</p>
            </div>
            <a href="{{ route('layanan.index') }}" class="primary-btn mt-3 sm:mt-0">
                Ajukan Layanan Baru
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
