<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Manajemen permohonan</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">Detail Permohonan</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.permohonan.index') }}" class="secondary-btn">Kembali</a>
                <a href="{{ route('admin.permohonan.edit', $permohonan) }}" class="primary-btn">Perbarui Status</a>
            </div>
        </div>
    </x-slot>

    <div class="workspace-page">
        <div class="grid gap-5 lg:grid-cols-3">
            <section class="soft-card p-5 lg:col-span-2 sm:p-6">
                <div class="mb-6 flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-5">
                    <div>
                        <p class="text-sm text-slate-500">Nomor tiket</p>
                        <p class="mt-1 font-mono text-xl font-bold text-slate-900">{{ $permohonan->no_tiket }}</p>
                    </div>
                    <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold
                        @if(strtolower($permohonan->status) == 'diajukan') bg-amber-100 text-amber-800
                        @elseif(strtolower($permohonan->status) == 'verifikasi') bg-sky-100 text-sky-800
                        @elseif(strtolower($permohonan->status) == 'proses') bg-violet-100 text-violet-800
                        @elseif(strtolower($permohonan->status) == 'selesai') bg-emerald-100 text-emerald-800
                        @else bg-rose-100 text-rose-800 @endif">
                        {{ ucfirst($permohonan->status) }}
                    </span>
                </div>

                <h3 class="text-base font-semibold text-slate-900">Informasi permohonan</h3>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Layanan</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ $permohonan->layanan->nama_layanan }}</dd></div>
                    <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal pengajuan</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d M Y') }}</dd></div>
                    <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Unit kerja</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ $permohonan->unit_kerja ?: '—' }}</dd></div>
                    <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tiket admin</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ $permohonan->no_tiket_admin ?: 'Belum tersedia' }}</dd></div>
                </dl>

                @if($permohonan->catatan_admin)
                    <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <h3 class="text-sm font-semibold text-amber-900">Catatan admin</h3>
                        <p class="mt-1 text-sm leading-6 text-amber-800">{{ $permohonan->catatan_admin }}</p>
                    </div>
                @endif
            </section>

            <aside class="soft-card p-5 sm:p-6">
                <h3 class="text-base font-semibold text-slate-900">Data pemohon</h3>
                <dl class="mt-4 space-y-4 text-sm">
                    <div><dt class="text-slate-500">Nama</dt><dd class="mt-1 font-semibold text-slate-800">{{ $permohonan->user ? $permohonan->user->name : $permohonan->nama }}</dd></div>
                    <div><dt class="text-slate-500">Nomor HP</dt><dd class="mt-1 font-semibold text-slate-800">{{ $permohonan->user ? $permohonan->user->no_hp : $permohonan->no_hp }}</dd></div>
                    <div><dt class="text-slate-500">ID permohonan</dt><dd class="mt-1 font-mono font-semibold text-slate-800">#{{ $permohonan->id }}</dd></div>
                </dl>
            </aside>

            <section class="soft-card p-5 lg:col-span-3 sm:p-6">
                <h3 class="text-base font-semibold text-slate-900">Lampiran persyaratan</h3>
                @if($permohonan->lampiranPermohonan->count() > 0)
                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        @foreach($permohonan->lampiranPermohonan as $lampiran)
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm font-medium text-slate-700">{{ $lampiran->persyaratan->nama_persyaratan }}</p>
                                <a href="{{ Storage::url($lampiran->file_path) }}" target="_blank" class="text-sm font-semibold text-emerald-700 hover:text-emerald-900">Lihat file</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-3 text-sm text-slate-500">Tidak ada lampiran yang diunggah.</p>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
