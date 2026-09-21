<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600">Monitoring</p>
                <h2 class="mt-0.5 text-lg font-semibold text-slate-900">Penelusuran Permohonan — {{ $permohonan->no_tiket }}</h2>
            </div>
            <a href="{{ route('pimpinan.monitoring.index') }}" class="secondary-btn">Kembali ke Monitoring</a>
        </div>
    </x-slot>

    <div class="workspace-page">
        <div class="space-y-3">

            <div class="flash-banner rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm text-emerald-800">
                Mode monitoring (read-only). Halaman ini untuk menelusuri seluruh perjalanan permohonan — tidak ada aksi yang bisa diubah dari sini.
            </div>

            <x-permohonan-timeline :permohonan="$permohonan" />

            <!-- Info Permohonan -->
            <div class="soft-card p-4 sm:p-5">
                <div class="mb-4 flex items-start justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <p class="text-xs text-slate-400">No. Tiket</p>
                        <p class="mt-0.5 font-mono text-base font-semibold text-slate-900">{{ $permohonan->no_tiket }}</p>
                    </div>
                    <x-status-badge :status="$permohonan->status" class="shrink-0" />
                </div>

                <h3 class="text-[15px] font-semibold text-slate-900">
                    {{ $permohonan->nama_layanan_label }}
                    @if($permohonan->is_manual)
                        <span class="ml-1 rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Layanan di luar katalog</span>
                    @endif
                </h3>

                <dl class="mt-4 grid grid-cols-1 gap-x-6 gap-y-3 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-slate-400">Pemohon</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->user ? $permohonan->user->name : $permohonan->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Sumber Pengajuan</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->sumber_pengajuan === 'walk_in' ? 'Walk-in (loket)' : 'Online' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Seksi Penanggung Jawab</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->seksi_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Sedang Berada di</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->lokasi_saat_ini }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-slate-400">Tanggal Pengajuan</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d F Y') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Persyaratan & Dokumen Hasil: dropdown, terbuka default -->
            @if($permohonan->lampiranPermohonan->count() > 0)
            <details class="soft-card group" open>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">Persyaratan dari Pemohon <span class="ml-1 font-normal text-slate-400">({{ $permohonan->lampiranPermohonan->count() }})</span></span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                    <x-lampiran-permohonan-list :permohonan="$permohonan" />
                </div>
            </details>
            @endif

            @if($permohonan->lampiranHasil->count() > 0)
            <details class="soft-card group" open>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">Dokumen Hasil <span class="ml-1 font-normal text-slate-400">({{ $permohonan->lampiranHasil->count() }})</span></span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                    <x-lampiran-hasil-list :permohonan="$permohonan" />
                </div>
            </details>
            @endif

            <!-- Riwayat & Log: dropdown, tertutup default -->
            <details class="soft-card group">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">Riwayat &amp; Log Aktivitas</span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="space-y-4 border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                    @if($permohonan->disposisi->count() > 0)
                    <div>
                        <h4 class="mb-2.5 text-sm font-semibold text-slate-700">Riwayat Disposisi</h4>
                        <div class="space-y-2.5">
                            @foreach($permohonan->disposisi as $d)
                            <div class="border-l-2 border-emerald-400 pl-3 py-0.5">
                                <p class="text-sm font-medium text-slate-800">Ke {{ $d->seksi->nama_seksi }}</p>
                                <p class="text-xs text-slate-500">
                                    Dikirim {{ $d->tanggal_disposisi->format('d/m/Y H:i') }} oleh {{ $d->petugasPengirim->name ?? '-' }}
                                    @if($d->tanggal_diterima) &middot; Diterima {{ $d->tanggal_diterima->format('d/m/Y H:i') }} oleh {{ $d->petugasPenerima->name ?? '-' }} @else &middot; <span class="text-amber-600">belum diterima seksi</span> @endif
                                    @if($d->tanggal_selesai_seksi) &middot; Selesai {{ $d->tanggal_selesai_seksi->format('d/m/Y H:i') }} @endif
                                </p>
                                @if($d->catatan)<p class="text-sm text-slate-600 mt-1">{{ $d->catatan }}</p>@endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div>
                        <h4 class="mb-2.5 text-sm font-semibold text-slate-700">Timeline Lengkap</h4>
                        <p class="mb-2.5 text-xs text-slate-500">Log ini bersifat permanen (tidak bisa diedit/dihapus siapa pun) — jadi bisa dipakai untuk menelusuri di titik mana suatu kendala terjadi.</p>
                        <ol class="relative ml-2 border-l border-slate-200">
                            @foreach($permohonan->riwayatStatus as $r)
                            <li class="mb-3 ml-4">
                                <div class="absolute -left-1 mt-1.5 h-1.5 w-1.5 rounded-full bg-emerald-500"></div>
                                <time class="text-xs text-slate-400">{{ $r->created_at->format('d/m/Y H:i:s') }}</time>
                                <p class="text-sm font-medium"><x-status-badge :status="$r->status" /></p>
                                <p class="text-xs text-slate-500">oleh {{ $r->petugas->name ?? 'Sistem (guest)' }}</p>
                                @if($r->catatan)<p class="text-sm text-slate-600 mt-0.5">{{ $r->catatan }}</p>@endif
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </details>

        </div>
    </div>
</x-app-layout>
