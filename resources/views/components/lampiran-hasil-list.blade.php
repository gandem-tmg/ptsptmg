@props(['permohonan'])

@if($permohonan->lampiranHasil->isNotEmpty())
<div class="space-y-2">
    @foreach($permohonan->lampiranHasil as $lampiran)
    <div class="flex items-center justify-between gap-3 rounded-xl bg-emerald-50 px-4 py-3">
        <div class="min-w-0">
            <p class="truncate text-sm font-medium text-slate-900">{{ $lampiran->nama_dokumen }}</p>
            <p class="truncate text-xs text-slate-500">
                {{ $lampiran->tanggal_unggah?->format('d M Y') }}
                @if($permohonan->status !== 'selesai')
                    &middot; <span class="text-amber-600">menunggu verifikasi akhir PTSP</span>
                @endif
            </p>
        </div>
        <a href="{{ route('lampiranHasil.download', $lampiran) }}" class="shrink-0 text-sm font-medium text-emerald-600 hover:text-emerald-800">
            Unduh
        </a>
    </div>
    @endforeach
</div>
@endif
