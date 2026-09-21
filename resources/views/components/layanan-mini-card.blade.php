@props(['layanan'])

<a href="{{ route('layanan.show', $layanan) }}" class="soft-card block bg-white p-5 transition hover:shadow-lg">
    <div class="mb-2 flex items-start justify-between gap-2">
        <h3 class="font-semibold text-slate-900">{{ $layanan->nama_layanan }}</h3>
        @if($layanan->tipe_pelaksanaan)
        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide
            @if($layanan->tipe_pelaksanaan == 'full_digital') bg-green-100 text-green-700
            @elseif($layanan->tipe_pelaksanaan == 'perlu_fisik') bg-amber-100 text-amber-700
            @else bg-sky-100 text-sky-700 @endif">
            @if($layanan->tipe_pelaksanaan == 'full_digital') Full Online
            @elseif($layanan->tipe_pelaksanaan == 'perlu_fisik') Perlu Hadir
            @else Sistem Nasional @endif
        </span>
        @endif
    </div>
    <p class="line-clamp-2 text-sm text-slate-600">{{ $layanan->deskripsi ?: 'Klik untuk lihat persyaratan lengkap.' }}</p>
    <div class="mt-3 flex items-center justify-between">
        <p class="text-xs text-slate-400">{{ $layanan->persyaratan->count() }} persyaratan</p>
        @if($layanan->jenis_layanan_label)
        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500">{{ $layanan->jenis_layanan_label }}</span>
        @endif
    </div>
</a>
