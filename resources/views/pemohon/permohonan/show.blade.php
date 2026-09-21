<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Detail Permohonan</h2>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-3 py-2">

        @error('batal')
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm text-rose-800">{{ $message }}</div>
        @enderror

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

            <h3 class="text-[15px] font-semibold text-slate-900">{{ $permohonan->layanan->nama_layanan }}</h3>

            <dl class="mt-4 grid grid-cols-1 gap-x-6 gap-y-3 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-xs text-slate-400">Tanggal Pengajuan</dt>
                    <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d F Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Sedang Ditangani</dt>
                    <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->lokasi_saat_ini }}</dd>
                </div>
                @if($permohonan->deskripsi)
                <div class="sm:col-span-2">
                    <dt class="text-xs text-slate-400">Deskripsi</dt>
                    <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->deskripsi }}</dd>
                </div>
                @endif
            </dl>

            @if($permohonan->catatan_admin)
                <div class="mt-3 rounded-lg bg-amber-50 px-3.5 py-2.5 text-amber-800">
                    <span class="text-xs font-semibold uppercase tracking-wide">Catatan Petugas</span>
                    <p class="mt-0.5 text-sm">{{ $permohonan->catatan_admin }}</p>
                </div>
            @endif
        </div>

        <!-- Persyaratan & Lampiran: dropdown, terbuka default -->
        <details class="soft-card group" open>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                <span class="text-[15px] font-semibold text-slate-900">Persyaratan yang Anda Kirimkan</span>
                <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                <x-lampiran-permohonan-list :permohonan="$permohonan" />
            </div>
        </details>

        <!-- Dokumen Hasil: dropdown, terbuka default -->
        @if($permohonan->lampiranHasil->isNotEmpty())
        <details class="soft-card group" open>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                <span class="text-[15px] font-semibold text-slate-900">Dokumen Hasil</span>
                <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                <x-lampiran-hasil-list :permohonan="$permohonan" />
            </div>
        </details>
        @endif

        <div class="flex items-center justify-between">
            <a href="{{ route('pemohon.permohonan.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">
                &larr; Kembali
            </a>
            <div class="flex items-center gap-3">
                @if(in_array($permohonan->status, ['diajukan', 'didisposisikan']))
                    <form method="POST" action="{{ route('pemohon.permohonan.batalkan', $permohonan) }}" onsubmit="return confirm('Batalkan permohonan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-semibold text-rose-500 hover:text-rose-700">Batalkan Permohonan</button>
                    </form>
                @elseif($permohonan->status === 'selesai' && !$permohonan->surveiRespon)
                    <a href="{{ config('skm.external_url') }}" target="_blank" rel="noopener" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                        Isi Survei Kepuasan
                    </a>
                @endif
                <a href="{{ route('pemohon.permohonan.pdf', $permohonan) }}" class="secondary-btn">
                    Unduh Bukti Pengajuan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
