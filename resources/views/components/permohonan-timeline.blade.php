@props(['permohonan'])

@php
    // Tahapan baku (resmi) alur permohonan — dipakai konsisten di semua halaman.
    $steps = [
        'diterima' => 'Permohonan Diterima',
        'disposisi' => 'Disposisi Seksi',
        'diproses' => 'Proses Seksi',
        'selesai_seksi' => 'Selesai di Seksi',
        'verifikasi_akhir' => 'Verifikasi Akhir',
        'selesai' => 'Layanan Selesai',
    ];
    $stepKeys = array_keys($steps);

    // Pemetaan status sistem -> tahap baku di atas.
    $statusToStep = [
        'diajukan' => 'diterima',
        'verifikasi_ptsp' => 'diterima',
        'verifikasi' => 'diterima',
        'didisposisikan' => 'disposisi',
        'diproses_seksi' => 'diproses',
        'proses' => 'diproses',
        'selesai_seksi' => 'selesai_seksi',
        'verifikasi_akhir' => 'verifikasi_akhir',
        'selesai' => 'selesai',
    ];

    $isTerminalIssue = in_array($permohonan->status, ['ditolak', 'dikembalikan', 'dibatalkan']);

    // Cari tahap tertinggi yang pernah tercapai dari riwayat status.
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
            'dibatalkan' => 'Permohonan Dibatalkan oleh Pemohon',
            default => 'Permohonan Dikembalikan — Perlu Dilengkapi',
        };
    @endphp
    <div class="mb-3 flex items-start gap-3 rounded-lg border px-4 py-3 text-sm {{ $bannerClass }}">
        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <div>
            <p class="font-semibold">{{ $bannerTitle }}</p>
            @if($lastNote && $lastNote->catatan)
                <p class="mt-0.5">{{ $lastNote->catatan }}</p>
            @endif
        </div>
    </div>
@endif

<!-- Grid responsif: 3 kolom di HP (jadi 2 baris, tanpa scroll), 6 kolom di layar lebar -->
<div class="soft-card mb-4 p-3 sm:p-4">
    <div class="grid grid-cols-3 gap-y-3 sm:grid-cols-6 sm:gap-y-0">
        @foreach($steps as $key => $label)
            @php
                $idx = $loop->index;
                $isDone = $idx < $maxIndex || ($idx === $maxIndex && $isTerminalIssue);
                $isCurrent = !$isTerminalIssue && $idx === $currentIndex;
            @endphp
            <div class="flex flex-col items-center px-1 text-center">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold
                    {{ $isCurrent ? 'bg-emerald-600 text-white ring-2 ring-emerald-100' : ($isDone ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400') }}">
                    @if($isDone && !$isCurrent)
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @else
                        {{ $idx + 1 }}
                    @endif
                </span>
                <span class="mt-1.5 text-[11px] font-medium leading-tight {{ $isCurrent ? 'text-emerald-700' : ($isDone ? 'text-slate-700' : 'text-slate-400') }}">
                    {{ $label }}
                </span>
            </div>
        @endforeach
    </div>
</div>
