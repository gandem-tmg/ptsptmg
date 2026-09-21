@props(['permohonan', 'routePrefix' => null, 'showSeksi' => true])

@php
$url = $routePrefix ? route($routePrefix . '.permohonan.show', $permohonan) : '#';
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-emerald-300 hover:shadow-md">
    <a href="{{ $url }}" class="block">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-900">
                    {{ $permohonan->nama_layanan_label }}
                    @if($permohonan->is_manual)
                        <span class="ml-1 rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-semibold text-amber-700">Manual</span>
                    @endif
                </p>
                <p class="mt-0.5 truncate text-xs text-slate-500">
                    {{ $permohonan->no_tiket }} &middot; {{ $permohonan->user->name ?? $permohonan->nama }}
                </p>
            </div>
            <x-status-badge :status="$permohonan->status" class="shrink-0 whitespace-nowrap" />
        </div>
        <div class="mt-3 flex items-center justify-between gap-2 text-xs text-slate-400">
            <span class="inline-flex items-center gap-1">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                {{ $permohonan->tanggal_pengajuan?->format('d M Y') }}
            </span>
            @if($showSeksi)
            <span class="truncate rounded-full bg-slate-100 px-2 py-0.5 font-medium text-slate-500">
                {{ $permohonan->lokasi_saat_ini }}
            </span>
            @endif
        </div>
    </a>

    @isset($actions)
        <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
            {{ $actions }}
        </div>
    @endisset
</div>
