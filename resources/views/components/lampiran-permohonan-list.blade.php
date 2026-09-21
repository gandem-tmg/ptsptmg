@props(['permohonan'])

@if($permohonan->is_manual)
    <p class="mb-3 text-xs text-slate-500">Layanan di luar katalog (input manual) — kelengkapan berkas dicatat secara keseluruhan, bukan per item persyaratan.</p>
    <div class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-4 py-3">
        <p class="text-sm font-medium text-slate-900">Status Kelengkapan Berkas</p>
        @if($permohonan->kelengkapan_manual === 'lengkap')
            <span class="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-emerald-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Lengkap
            </span>
        @else
            <span class="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-amber-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86l-8.48 14.7A1 1 0 002.66 20h18.68a1 1 0 00.86-1.44l-8.48-14.7a1 1 0 00-1.72 0z"/></svg>
                Belum Lengkap
            </span>
        @endif
    </div>
@elseif($permohonan->lampiranPermohonan->isEmpty())
    @if($permohonan->sumber_pengajuan === 'walk_in')
        <p class="mb-3 text-xs text-slate-500">Pengajuan offline — kelengkapan persyaratan sudah dicek fisik & dikonfirmasi langsung oleh petugas loket saat pengajuan.</p>
        <div class="space-y-2">
            @forelse($permohonan->layanan->persyaratan as $p)
            <div class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-4 py-3">
                <p class="text-sm font-medium text-slate-900">{{ $p->nama_persyaratan }}</p>
                <span class="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-emerald-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Sudah diverifikasi
                </span>
            </div>
            @empty
            <p class="text-sm text-slate-500">Layanan ini tidak memerlukan persyaratan khusus.</p>
            @endforelse
        </div>
    @else
        <p class="text-sm text-slate-500">Belum ada lampiran yang diunggah.</p>
    @endif
@else
    <div class="space-y-2">
        @foreach($permohonan->lampiranPermohonan as $lampiran)
        <div class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-4 py-3">
            <div class="min-w-0">
                <p class="truncate text-sm font-medium text-slate-900">{{ $lampiran->persyaratan->nama_persyaratan ?? 'Dokumen' }}</p>
                @if($lampiran->persyaratan && $lampiran->persyaratan->wajib)
                    <span class="text-xs text-rose-600">Wajib</span>
                @endif
            </div>
            <a href="{{ route('lampiran.download', $lampiran) }}" class="shrink-0 text-sm font-medium text-emerald-600 hover:text-emerald-800">
                Unduh
            </a>
        </div>
        @endforeach
    </div>
@endif
