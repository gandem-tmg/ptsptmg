<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600">{{ Auth::user()->seksi->nama_seksi ?? 'Seksi' }}</p>
                <h2 class="mt-0.5 text-lg font-semibold text-slate-900">Tindak Lanjut Permohonan — {{ $permohonan->no_tiket }}</h2>
            </div>
            <a href="{{ route('seksi.permohonan.index') }}" class="secondary-btn">Kembali</a>
        </div>
    </x-slot>

    <div class="workspace-page">
        <div class="space-y-3">

            @if(session('success'))
                <div class="flash-banner rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm text-emerald-800">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="flash-banner rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm text-rose-700">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                        <dt class="text-xs text-slate-400">No. HP</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ ($permohonan->user->no_hp ?? null) ?: ($permohonan->no_hp ?: '-') }}</dd>
                    </div>
                    @unless($permohonan->is_manual)
                    <div>
                        <dt class="text-xs text-slate-400">Tipe Pelaksanaan</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ str_replace('_', ' ', $permohonan->layanan->tipe_pelaksanaan ?? '-') }}</dd>
                    </div>
                    @endunless
                    <div>
                        <dt class="text-xs text-slate-400">Tanggal Pengajuan</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d F Y') }}</dd>
                    </div>
                    @if($permohonan->is_manual && $permohonan->deskripsi_layanan_manual)
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-slate-400">Deskripsi Layanan</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->deskripsi_layanan_manual }}</dd>
                    </div>
                    @endif
                    @if($permohonan->deskripsi)
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-slate-400">Deskripsi</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->deskripsi }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Aksi Seksi: dipindah ke urutan atas & ditonjolkan -->
            <div class="soft-card border-l-4 border-l-emerald-500 p-4 sm:p-5">
                <h3 class="text-[15px] font-semibold text-slate-900">Aksi Seksi</h3>

                @if($permohonan->status === 'didisposisikan')
                    <p class="mt-1 text-sm text-slate-600 mb-3">Permohonan baru masuk dari PTSP. Terima disposisi ini untuk mulai ditindaklanjuti.</p>
                    <form method="POST" action="{{ route('seksi.permohonan.terima', $permohonan) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="primary-btn">Terima Disposisi</button>
                    </form>

                @elseif($permohonan->status === 'diproses_seksi')
                    <div class="mt-1 space-y-4">
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-slate-800">Catat Progres</h4>
                            <p class="mb-2 text-xs text-slate-500">
                                Contoh: "sedang diproses via aplikasi nasional", "menunggu kehadiran pemohon untuk verifikasi dokumen asli", "menunggu tanda tangan pejabat".
                                Ini tidak mengubah status utama, hanya menambah catatan checkpoint di riwayat.
                            </p>
                            <form method="POST" action="{{ route('seksi.permohonan.progres', $permohonan) }}" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="catatan" class="form-input flex-1" placeholder="Catatan progres..." required>
                                <button type="submit" class="primary-btn shrink-0">Catat</button>
                            </form>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <h4 class="mb-1 text-sm font-semibold text-slate-800">Selesaikan &amp; Kembalikan ke PTSP</h4>

                            @if(!$permohonan->is_manual && $permohonan->layanan->perlu_dokumen_hasil)
                                <p class="mb-2.5 text-xs text-slate-500">Layanan ini menghasilkan dokumen/surat resmi — unggah hasilnya di sini.</p>
                                <form method="POST" action="{{ route('seksi.permohonan.selesai', $permohonan) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-2.5 grid grid-cols-1 gap-3 md:grid-cols-2">
                                        <div>
                                            <label class="field-label">Nama Dokumen Hasil (opsional)</label>
                                            <input type="text" name="nama_dokumen" class="form-input" placeholder="Contoh: Surat Rekomendasi Bantuan Madrasah">
                                        </div>
                                        <div>
                                            <label class="field-label">Unggah Dokumen Hasil (opsional)</label>
                                            <input type="file" name="dokumen_hasil" class="form-input">
                                        </div>
                                    </div>
                                    <label class="field-label">Catatan Penyelesaian (opsional)</label>
                                    <input type="text" name="catatan" class="form-input">
                                    <button type="submit" class="primary-btn mt-3">
                                        Selesai — Kembalikan ke PTSP
                                    </button>
                                </form>
                            @else
                                <p class="mb-2.5 text-xs text-slate-500">Layanan ini tidak memerlukan dokumen keluaran (misal: konsultasi/bimbingan) — cukup tandai selesai setelah pemohon dilayani langsung.</p>
                                <form method="POST" action="{{ route('seksi.permohonan.selesai', $permohonan) }}">
                                    @csrf
                                    <label class="field-label">Catatan Penyelesaian (opsional)</label>
                                    <input type="text" name="catatan" class="form-input" placeholder="Contoh: Sudah dikonsultasikan langsung, pemohon paham solusinya">
                                    <button type="submit" class="primary-btn mt-3">
                                        Tandai Selesai — Kembalikan ke PTSP
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                @else
                    <p class="mt-1 text-sm text-slate-600">Permohonan sudah tidak lagi di seksi ini (status: <x-status-badge :status="$permohonan->status" />).</p>
                @endif
            </div>

            <!-- Persyaratan dari Pemohon: dropdown, terbuka default -->
            @if($permohonan->lampiranPermohonan->count() > 0 || $permohonan->is_manual)
            <details class="soft-card group" open>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">
                        Persyaratan dari Pemohon
                        @unless($permohonan->is_manual)
                            <span class="ml-1 font-normal text-slate-400">({{ $permohonan->lampiranPermohonan->count() }})</span>
                        @endunless
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                    <x-lampiran-permohonan-list :permohonan="$permohonan" />
                </div>
            </details>
            @endif

            <!-- Riwayat Status: dropdown, tertutup default -->
            <details class="soft-card group">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">Riwayat Status</span>
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
                            @if($r->catatan)<p class="text-sm text-slate-600 mt-0.5">{{ $r->catatan }}</p>@endif
                        </li>
                        @endforeach
                    </ol>
                </div>
            </details>

        </div>
    </div>
</x-app-layout>
