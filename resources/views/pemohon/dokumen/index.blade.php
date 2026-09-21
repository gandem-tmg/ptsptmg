<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Dokumen Saya</h2>
    </x-slot>

    <div class="space-y-4 py-2">
        @if($dokumen->isEmpty())
            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
                Belum ada dokumen resmi yang terbit. Dokumen akan muncul di sini otomatis begitu salah satu permohonan Anda selesai diproses.
            </div>
        @else
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($dokumen as $item)
                <div class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-emerald-300 hover:shadow-md">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $item->nama_dokumen }}</p>
                            <p class="mt-0.5 truncate text-xs text-slate-500">
                                {{ $item->permohonan->layanan->nama_layanan ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-xs text-slate-400">
                        <span>{{ $item->tanggal_unggah?->format('d M Y') }}</span>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('pemohon.permohonan.show', $item->permohonan_id) }}" class="font-semibold text-slate-500 hover:text-slate-700">
                                Lihat Permohonan
                            </a>
                            <a href="{{ route('lampiranHasil.download', $item) }}" class="font-semibold text-emerald-600 hover:text-emerald-700">
                                Unduh
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between pt-2 text-sm text-slate-500">
                <span>{{ $dokumen->firstItem() }}–{{ $dokumen->lastItem() }} dari {{ $dokumen->total() }}</span>
                {{ $dokumen->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
