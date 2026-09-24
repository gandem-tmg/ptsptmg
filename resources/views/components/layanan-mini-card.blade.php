@props(['layanan'])

@php
    // Kolom standar pelayanan berupa teks bebas (bisa panjang). Di kartu cukup
    // baris pertama yang dipotong; teks lengkap tetap ada di halaman detail.
    $ringkas = fn ($teks, $batas) => \Illuminate\Support\Str::of((string) $teks)->replace("\r", '')->trim()->before("\n")->trim()->limit($batas)->toString();

    $jangkaWaktu = $ringkas($layanan->jangka_waktu_pelayanan, 40);
    $biaya = $ringkas($layanan->biaya_tarif, 32);

    // Penulisan "gratis" bervariasi antar seksi — samakan supaya kartu konsisten.
    if ($biaya !== '' && preg_match('/^(gratis|tidak dipungut biaya|tidak ada biaya|tanpa biaya|nihil)/i', $biaya)) {
        $biaya = 'Gratis';
    }
@endphp

<a href="{{ route('layanan.show', $layanan) }}" class="soft-card flex h-full flex-col bg-white p-5 transition hover:shadow-lg">
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

    @if($layanan->deskripsi)
    <p class="line-clamp-2 text-sm text-slate-600">{{ $layanan->deskripsi }}</p>
    @endif

    @if($jangkaWaktu !== '' || $biaya !== '')
    {{-- Jangka waktu · biaya, mis. "±3 hari kerja · Gratis" --}}
    <p class="mt-2 flex items-center gap-1.5 text-[13px] font-medium text-slate-700">
        <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" /></svg>
        <span class="min-w-0">{{ collect([$jangkaWaktu, $biaya])->filter()->implode(' · ') }}</span>
    </p>
    @endif

    <div class="mt-auto flex items-center justify-between pt-3">
        <p class="text-xs text-slate-500">{{ $layanan->persyaratan->count() }} persyaratan</p>
        @if($layanan->jenis_layanan_label)
        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-600">{{ $layanan->jenis_layanan_label }}</span>
        @endif
    </div>
</a>
