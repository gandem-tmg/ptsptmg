<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Survei Kepuasan</h2>
    </x-slot>

    <div class="space-y-6 py-2">
        @if(session('success'))
            <div class="flash-banner rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        @if($belumDiisi->isNotEmpty())
            <div class="overflow-hidden rounded-2xl border border-amber-200 bg-amber-50/60">
                <div class="flex items-start gap-3 px-5 py-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-amber-900">
                            Ada {{ $belumDiisi->count() }} layanan yang sudah selesai, tapi belum Anda beri penilaian
                        </p>
                        <p class="mt-0.5 text-xs text-amber-700">Masukan Anda membantu kami menjaga & meningkatkan kualitas pelayanan. Cuma butuh waktu ± 1 menit.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($belumDiisi as $permohonan)
                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-emerald-300 hover:shadow-md">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-semibold text-slate-900">{{ $permohonan->layanan->nama_layanan ?? '-' }}</p>
                                <span class="shrink-0 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">Selesai</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $permohonan->no_tiket }} &middot; {{ $permohonan->tanggal_pengajuan?->format('d M Y') }}
                            </p>
                        </div>
                        <a href="{{ config('skm.external_url') }}" target="_blank" rel="noopener"
                           class="primary-btn mt-4 w-full text-xs">
                            Isi Survei Kepuasan
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </span>
                <p class="mt-3 text-sm font-semibold text-slate-900">Semua sudah terisi, terima kasih!</p>
                <p class="mt-1 text-xs text-slate-500">Tidak ada layanan selesai yang masih menunggu penilaian dari Anda.</p>
            </div>
        @endif

        @if($sudahDiisi->isNotEmpty())
            <div>
                <h3 class="mb-3 text-sm font-semibold text-slate-700">Riwayat Survei yang Sudah Diisi</h3>
                <div class="divide-y divide-slate-100 rounded-2xl border border-slate-200 bg-white">
                    @foreach($sudahDiisi as $permohonan)
                        <div class="flex items-center justify-between gap-3 px-5 py-3.5">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $permohonan->layanan->nama_layanan ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $permohonan->no_tiket }} &middot; diisi {{ $permohonan->surveiRespon->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-emerald-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Terisi
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center text-xs text-slate-500">
            Belum pernah pakai layanan kami, atau ingin memberi masukan umum?
            <a href="{{ config('skm.external_url') }}" target="_blank" rel="noopener" class="font-semibold text-emerald-600 hover:text-emerald-700">Isi survei umum &rarr;</a>
        </div>
    </div>
</x-app-layout>
