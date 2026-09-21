@props(['permohonan'])

@php
    // Versi publik SENGAJA cuma 3 tahap besar — pemohon yang cek tanpa
    // login tidak perlu tahu detail alur internal (disposisi ke seksi,
    // verifikasi akhir, dst). Detail lengkap tetap ada di akun pemohon
    // (lihat komponen x-permohonan-timeline yang dipakai di sana).
    $steps = [
        'diterima' => 'Permohonan Diterima',
        'diproses' => 'Sedang Diproses',
        'selesai' => 'Selesai',
    ];
    $stepKeys = array_keys($steps);

    $statusToStep = [
        'diajukan' => 'diterima',
        'verifikasi_ptsp' => 'diterima',
        'verifikasi' => 'diterima',
        'didisposisikan' => 'diproses',
        'diproses_seksi' => 'diproses',
        'proses' => 'diproses',
        'selesai_seksi' => 'diproses',
        'verifikasi_akhir' => 'diproses',
        'selesai' => 'selesai',
    ];

    $isTerminalIssue = in_array($permohonan->status, ['ditolak', 'dikembalikan', 'dibatalkan']);

    $maxIndex = 0;
    foreach ($permohonan->riwayatStatus ?? [] as $r) {
        $stepKey = $statusToStep[$r->status] ?? null;
        if ($stepKey) {
            $idx = array_search($stepKey, $stepKeys);
            if ($idx !== false && $idx > $maxIndex) {
                $maxIndex = $idx;
            }
        }
    }

    $currentIndex = -1;
    if (!$isTerminalIssue) {
        $currentKey = $statusToStep[$permohonan->status] ?? 'diterima';
        $currentIndex = array_search($currentKey, $stepKeys);
        $maxIndex = max($maxIndex, $currentIndex);
    }
@endphp

@if($isTerminalIssue)
    @php
        $lastNote = $permohonan->riwayatStatus->last();
        $bannerClass = match ($permohonan->status) {
            'ditolak' => 'border-rose-200 bg-rose-50 text-rose-800',
            'dibatalkan' => 'border-slate-200 bg-slate-50 text-slate-600',
            default => 'border-amber-200 bg-amber-50 text-amber-800',
        };
        $bannerTitle = match ($permohonan->status) {
            'ditolak' => 'Permohonan Ditolak',
            'dibatalkan' => 'Permohonan Dibatalkan',
            default => 'Permohonan Dikembalikan — Perlu Dilengkapi',
        };
    @endphp
    <div class="mb-4 flex items-start gap-3 rounded-lg border px-4 py-3 text-sm {{ $bannerClass }}">
        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <div>
            <p class="font-semibold">{{ $bannerTitle }}</p>
            @if($lastNote && $lastNote->catatan)
                <p class="mt-0.5">{{ $lastNote->catatan }}</p>
            @endif
        </div>
    </div>
@endif

{{-- 3 kolom SAMA LEBAR (grid-cols-3 Tailwind = repeat(3, minmax(0,1fr)) —
     min-width:0 di situ penting, supaya teks label panjang TIDAK ikut
     melebarkan kolom lingkaran seperti yang terjadi kalau pakai kolom
     "auto". Garis penghubung digambar terpisah pakai posisi persentase
     tetap (bukan ikut ukuran kolom), jadi selalu simetris. --}}
<div class="soft-card mb-4 p-4 sm:p-5">
    <div class="relative">
        <!-- Garis penghubung: dari tengah lingkaran 1↔2 dan 2↔3.
             top pakai inline style (bukan class Tailwind) supaya presisi di tengah
             lingkaran (h-8 = 32px, jadi tengah = 16px) dan tidak bergantung pada
             ke-generate-an ulang CSS. -->
        <div class="pointer-events-none absolute h-0.5 -translate-y-1/2 rounded-full {{ 1 <= $maxIndex ? 'bg-emerald-500' : 'bg-slate-100' }}" style="top: 16px; left: 16.667%; width: 33.333%;"></div>
        <div class="pointer-events-none absolute h-0.5 -translate-y-1/2 rounded-full {{ 2 <= $maxIndex ? 'bg-emerald-500' : 'bg-slate-100' }}" style="top: 16px; left: 50%; width: 33.333%;"></div>

        <div class="relative z-10 grid grid-cols-3">
            @foreach($steps as $key => $label)
                @php
                    $idx = $loop->index;
                    $isDone = $idx < $maxIndex || ($idx === $maxIndex && $isTerminalIssue);
                    $isCurrent = !$isTerminalIssue && $idx === $currentIndex;
                @endphp
                <div class="flex flex-col items-center gap-1.5 px-1">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold
                        {{ $isCurrent ? 'bg-emerald-600 text-white ring-2 ring-emerald-100' : ($isDone ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400') }}">
                        @if($isDone && !$isCurrent)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @elseif($key === 'diproses' && $isCurrent)
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                        @else
                            {{ $idx + 1 }}
                        @endif
                    </span>
                    <span class="text-center text-[11px] font-semibold leading-tight sm:text-xs {{ $isCurrent ? 'text-emerald-700' : ($isDone ? 'text-slate-700' : 'text-slate-400') }}">
                        {{ $label }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
