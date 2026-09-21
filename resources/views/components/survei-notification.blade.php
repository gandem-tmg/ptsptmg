@php
    // Ambil maksimal 5 buat preview di dropdown, tapi hitung total yang sebenarnya
    // buat badge (biar akurat walau yang belum isi lebih dari 5).
    $belumSurveiQuery = \App\Models\Permohonan::where('user_id', Auth::id())->selesaiBelumSurvei();
    $totalBelumSurvei = (clone $belumSurveiQuery)->count();
    $previewBelumSurvei = $totalBelumSurvei > 0
        ? (clone $belumSurveiQuery)->with('layanan')->latest('tanggal_pengajuan')->take(5)->get()
        : collect();
@endphp

<div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = ! open" type="button" class="relative rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700" aria-label="Notifikasi survei kepuasan">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
        @if($totalBelumSurvei > 0)
            <span class="absolute right-1 top-1 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold leading-none text-white">
                {{ $totalBelumSurvei > 9 ? '9+' : $totalBelumSurvei }}
            </span>
        @endif
    </button>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 z-50 mt-2 w-80 max-w-[90vw] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
            <p class="text-sm font-semibold text-slate-900">Survei Kepuasan</p>
            @if($totalBelumSurvei > 0)
                <span class="rounded-full bg-rose-50 px-2 py-0.5 text-[11px] font-semibold text-rose-600">{{ $totalBelumSurvei }} belum diisi</span>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($previewBelumSurvei as $permohonan)
                <a href="{{ route('pemohon.survei.index') }}" class="flex items-start gap-3 border-b border-slate-50 px-4 py-3 last:border-0 hover:bg-slate-50">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-medium text-slate-800">{{ $permohonan->layanan->nama_layanan ?? '-' }}</span>
                        <span class="block text-xs text-slate-400">Layanan selesai, belum ada penilaian dari Anda</span>
                    </span>
                </a>
            @empty
                <div class="px-4 py-8 text-center text-sm text-slate-400">
                    Tidak ada notifikasi. Semua survei sudah terisi 👍
                </div>
            @endforelse
        </div>

        @if($totalBelumSurvei > 0)
            <a href="{{ route('pemohon.survei.index') }}" class="block border-t border-slate-100 px-4 py-3 text-center text-sm font-semibold text-emerald-600 hover:bg-emerald-50">
                Lihat semua &rarr;
            </a>
        @endif
    </div>
</div>
