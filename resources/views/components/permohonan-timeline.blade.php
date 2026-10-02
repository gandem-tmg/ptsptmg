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

    // Precompute per-step state sekali di sini, dipakai untuk lingkaran maupun garis penghubung di markup.
    $stepStates = [];
    foreach ($stepKeys as $idx => $key) {
        $isDone = $idx < $maxIndex || ($idx === $maxIndex && $isTerminalIssue);
        $stepStates[] = [
            'label' => $steps[$key],
            'done' => $isDone,
            'current' => !$isTerminalIssue && $idx === $currentIndex,
        ];
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

{{--
    Ministepper satu baris. Lingkaran dan label ditaruh dalam KOLOM yang sama persis
    (flex-1 berisi keduanya) supaya label selalu center pas di bawah lingkarannya —
    garis penghubung digambar terpisah di belakang (absolute), bukan digabung satu
    wrapper dengan lingkaran, karena itu yang sebelumnya bikin lingkaran nempel ke
    kiri kolom sementara labelnya center, jadi keduanya tidak sejajar.
--}}
@php $n = count($stepStates); @endphp
<div class="soft-card mb-4 p-3 sm:p-4">
    <div class="relative">
        {{-- Garis penghubung: dari titik tengah lingkaran pertama sampai titik tengah lingkaran terakhir. --}}
        <div class="absolute top-3 flex h-0.5 -translate-y-1/2" style="left: {{ 100 / (2 * $n) }}%; right: {{ 100 / (2 * $n) }}%;">
            @for($i = 0; $i < $n - 1; $i++)
                <div class="flex-1 {{ $stepStates[$i]['done'] ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
            @endfor
        </div>

        <div class="relative flex">
            @foreach($stepStates as $idx => $state)
                <div class="flex flex-1 flex-col items-center gap-1.5">
                    <span class="relative flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[11px] font-bold
                        {{ $state['current'] ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : ($state['done'] ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400') }}"
                        title="{{ $state['label'] }}">
                        @if($state['done'] && !$state['current'])
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @else
                            {{ $idx + 1 }}
                        @endif
                    </span>
                    {{-- Label disembunyikan di HP (diganti ringkasan di bawah), tampil dari sm ke atas. --}}
                    <span class="hidden px-0.5 text-center text-[10.5px] font-medium leading-tight sm:block {{ $state['current'] ? 'text-emerald-700' : ($state['done'] ? 'text-slate-600' : 'text-slate-400') }}">
                        {{ $state['label'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Ringkasan langkah saat ini, khusus HP (menggantikan label penuh di atas). --}}
    @if($currentIndex >= 0)
    <p class="mt-1.5 text-center text-[12px] font-semibold text-emerald-700 sm:hidden">
        Langkah {{ $currentIndex + 1 }}/{{ $n }} &middot; {{ $stepStates[$currentIndex]['label'] }}
    </p>
    @endif
</div>
