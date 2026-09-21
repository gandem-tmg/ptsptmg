<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600">Manajemen permohonan</p>
                <h2 class="mt-0.5 text-lg font-semibold text-slate-900">Detail Permohonan</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.permohonan.index') }}" class="secondary-btn">Kembali</a>
                <a href="{{ route('admin.permohonan.edit', $permohonan) }}" class="primary-btn">Perbarui Status</a>
            </div>
        </div>
    </x-slot>

    <div class="workspace-page space-y-3">

        <x-permohonan-timeline :permohonan="$permohonan" />

        <div class="grid gap-3 lg:grid-cols-3">
            <section class="soft-card p-4 lg:col-span-2 sm:p-5">
                <div class="mb-4 flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <p class="text-xs text-slate-400">Nomor tiket</p>
                        <p class="mt-0.5 font-mono text-base font-semibold text-slate-900">{{ $permohonan->no_tiket }}</p>
                    </div>
                    <x-status-badge :status="$permohonan->status" class="text-xs" />
                </div>

                <h3 class="text-[15px] font-semibold text-slate-900">Informasi permohonan</h3>
                <dl class="mt-3 grid gap-x-6 gap-y-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-xs text-slate-400">Layanan</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->nama_layanan_label }}@if($permohonan->is_manual) <span class="ml-1 rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Manual</span>@endif</dd></div>
                    <div><dt class="text-xs text-slate-400">Tanggal pengajuan</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d M Y') }}</dd></div>
                    <div><dt class="text-xs text-slate-400">Sedang di Seksi</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->lokasi_saat_ini }}</dd></div>
                    <div><dt class="text-xs text-slate-400">Tiket admin</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->no_tiket_admin ?: 'Belum tersedia' }}</dd></div>
                </dl>

                @if($permohonan->catatan_admin)
                    <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-3.5">
                        <h3 class="text-sm font-semibold text-amber-900">Catatan admin</h3>
                        <p class="mt-1 text-sm leading-6 text-amber-800">{{ $permohonan->catatan_admin }}</p>
                    </div>
                @endif
            </section>

            <aside class="soft-card p-4 sm:p-5">
                <h3 class="text-[15px] font-semibold text-slate-900">Data pemohon</h3>
                <dl class="mt-3 space-y-3 text-sm">
                    <div><dt class="text-xs text-slate-400">Nama</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->user ? $permohonan->user->name : $permohonan->nama }}</dd></div>
                    <div><dt class="text-xs text-slate-400">Nomor HP</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->user ? $permohonan->user->no_hp : $permohonan->no_hp }}</dd></div>
                    <div><dt class="text-xs text-slate-400">ID permohonan</dt><dd class="mt-0.5 font-mono font-medium text-slate-800">#{{ $permohonan->id }}</dd></div>
                </dl>
            </aside>

            <details class="soft-card group lg:col-span-3" open>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">Persyaratan dari Pemohon</span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                    <x-lampiran-permohonan-list :permohonan="$permohonan" />
                </div>
            </details>

            @if($permohonan->lampiranHasil->isNotEmpty())
            <details class="soft-card group lg:col-span-3" open>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">Dokumen Hasil</span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                    <x-lampiran-hasil-list :permohonan="$permohonan" />
                </div>
            </details>
            @endif

            <details class="soft-card group lg:col-span-3">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">Log Riwayat Status</span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                    <ol class="relative ml-2 border-l border-slate-200">
                        @foreach($permohonan->riwayatStatus as $r)
                        <li class="mb-3 ml-4">
                            <div class="absolute -left-1 mt-1.5 h-1.5 w-1.5 rounded-full bg-emerald-500"></div>
                            <time class="text-xs text-slate-400">{{ $r->created_at->format('d/m/Y H:i') }}</time>
                            <p class="text-sm font-medium"><x-status-badge :status="$r->status" /></p>
                            <p class="text-xs text-slate-500">oleh {{ $r->petugas->name ?? 'Sistem' }}</p>
                            @if($r->catatan)<p class="mt-0.5 text-sm text-slate-600">{{ $r->catatan }}</p>@endif
                        </li>
                        @endforeach
                    </ol>
                </div>
            </details>
        </div>
    </div>
</x-app-layout>
