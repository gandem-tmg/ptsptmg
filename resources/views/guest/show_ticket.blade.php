<x-public-layout>
    <div class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="mb-5 text-center">
            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-700">
                Status Permohonan
            </span>
            <h1 class="mt-3 font-mono text-xl font-bold tracking-wide text-slate-900 sm:text-2xl">{{ $permohonan->no_tiket }}</h1>
        </div>

        <x-permohonan-timeline-public :permohonan="$permohonan" />

        @if(session('success'))
            <div class="flash-banner mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="flex items-start justify-between gap-3 border-b border-slate-100 p-4">
                <h3 class="font-semibold text-slate-900">{{ $permohonan->nama_layanan_label }}</h3>
                <x-status-badge :status="$permohonan->status" class="shrink-0" />
            </div>

            <dl class="grid grid-cols-1 gap-2.5 p-4 sm:grid-cols-2">
                <div class="flex items-start gap-3 rounded-lg bg-slate-50 p-3">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-slate-500 shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </span>
                    <div class="min-w-0">
                        <dt class="text-xs font-medium text-slate-600">Nama Pemohon</dt>
                        <dd class="mt-0.5 truncate text-sm font-semibold text-slate-800">{{ $permohonan->user->name ?? $permohonan->nama }}</dd>
                    </div>
                </div>
                <div class="flex items-start gap-3 rounded-lg bg-slate-50 p-3">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-slate-500 shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                    </span>
                    <div class="min-w-0">
                        <dt class="text-xs font-medium text-slate-600">Tanggal Pengajuan</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d F Y') }}</dd>
                    </div>
                </div>
                @unless(in_array($permohonan->status, \App\Models\Permohonan::STATUS_FINAL))
                <div class="flex items-start gap-3 rounded-lg bg-slate-50 p-3 sm:col-span-2">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-slate-500 shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" /></svg>
                    </span>
                    <div class="min-w-0">
                        <dt class="text-xs font-medium text-slate-600">Sedang Ditangani</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-slate-800">{{ $permohonan->lokasi_saat_ini }}</dd>
                    </div>
                </div>
                @endunless
            </dl>
        </div>

        {{-- Khusus permohonan yang sudah selesai & belum dinilai — termasuk
             permohonan offline/walk-in yang tidak punya akun, jadi ini
             satu-satunya jalur mereka mengisi survei kepuasan. Pengisian
             SKM internal sudah dimatikan, jadi tombol ini langsung ke
             link SKM eksternal (satu pintu). --}}
        @if($permohonan->status === 'selesai' && !$permohonan->surveiRespon)
            <div class="mt-4 flex items-center justify-between gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-emerald-800">Layanan Anda sudah selesai</p>
                    <p class="mt-0.5 text-xs text-emerald-700">Bantu kami menilai kualitas pelayanan lewat survei singkat.</p>
                </div>
                <a href="{{ config('skm.external_url') }}" target="_blank" rel="noopener" class="shrink-0 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                    Isi Survei
                </a>
            </div>
        @endif

        <div class="mt-6 text-center">
            <a href="{{ route('guest.searchTicket') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Cek Nomor Tiket Lain
            </a>
        </div>
    </div>
</x-public-layout>
