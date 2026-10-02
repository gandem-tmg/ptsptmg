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

        @if(session('success'))
            <div class="flash-banner mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="flash-banner mb-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm text-rose-700">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{--
            Layout dashboard yang sama dengan halaman Tindak Lanjut PTSP: timeline
            full-width nempel di atas, lalu 2 kolom di bawahnya (kiri konten, kanan
            aksi). Urutan SUMBER di bawah ini sengaja Aksi Seksi dulu, baru Timeline,
            baru konten kiri — supaya di HP urutan tampilnya Aksi Seksi paling atas.
            Di layar lg, grid row/col-start di bawah yang mengatur ulang posisi
            visualnya (lihat komentar yang sama di petugas/permohonan/show.blade.php).
        --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            <div class="space-y-3 lg:col-start-3 lg:row-start-2 lg:sticky lg:top-[9.5rem] lg:self-start">
                <div class="soft-card border-l-4 border-l-emerald-500 p-4 sm:p-5">
                    <h3 class="text-[15px] font-semibold text-slate-900">Aksi Seksi</h3>

                    @if($permohonan->status === 'didisposisikan')
                        <div class="mt-2 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50/60 p-3.5">
                            <span class="inline-flex shrink-0 rounded-lg bg-emerald-100 p-2 text-emerald-600">
                                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a1 1 0 00-.9.55l-.7 1.4a1 1 0 01-.9.55h-3a1 1 0 01-.9-.55l-.7-1.4a1 1 0 00-.9-.55H4" /></svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900">Permohonan baru masuk</p>
                                <p class="mt-0.5 text-xs text-slate-600">Dari PTSP. Terima disposisi ini untuk mulai ditindaklanjuti.</p>
                                <form method="POST" action="{{ route('seksi.permohonan.terima', $permohonan) }}" class="mt-2.5">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="primary-btn w-full justify-center">Terima Disposisi</button>
                                </form>
                            </div>
                        </div>

                    @elseif($permohonan->status === 'diproses_seksi')
                        <div class="mt-2 space-y-2.5">
                            {{-- Catat Progres & Selesaikan BUKAN pilihan yang saling eksklusif
                                 (progres bisa dicatat berkali-kali, baru nanti diselesaikan) —
                                 jadi dua kartu independen, bukan "pilih salah satu" seperti di
                                 halaman PTSP. --}}
                            <div x-data="{ buka: false }">
                                <button type="button" @click="buka = !buka"
                                    class="flex w-full items-start gap-3 rounded-xl border bg-white p-3.5 text-left transition"
                                    :class="buka ? 'border-sky-400 ring-1 ring-sky-400' : 'border-slate-200 hover:border-sky-300'">
                                    <span class="inline-flex shrink-0 rounded-lg bg-sky-50 p-2 text-sky-600">
                                        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                    </span>
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-900">Catat Progres</span>
                                        <span class="mt-0.5 block text-xs text-slate-500">Checkpoint singkat, tidak mengubah status utama.</span>
                                    </span>
                                </button>
                                <div x-show="buka" x-cloak class="rounded-xl border border-sky-100 bg-sky-50/40 p-3">
                                    <p class="text-xs text-sky-700 mb-2">
                                        Contoh: "sedang diproses via aplikasi nasional", "menunggu kehadiran pemohon untuk verifikasi dokumen asli", "menunggu tanda tangan pejabat".
                                    </p>
                                    <form method="POST" action="{{ route('seksi.permohonan.progres', $permohonan) }}" class="flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="catatan" class="form-input flex-1" placeholder="Catatan progres..." required>
                                        <button type="submit" class="primary-btn shrink-0">Catat</button>
                                    </form>
                                </div>
                            </div>

                            <div x-data="{ buka: false }">
                                <button type="button" @click="buka = !buka"
                                    class="flex w-full items-start gap-3 rounded-xl border bg-white p-3.5 text-left transition"
                                    :class="buka ? 'border-emerald-500 ring-1 ring-emerald-500' : 'border-slate-200 hover:border-emerald-300'">
                                    <span class="inline-flex shrink-0 rounded-lg bg-emerald-50 p-2 text-emerald-600">
                                        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-900">Selesaikan &amp; Kembalikan ke PTSP</span>
                                        <span class="mt-0.5 block text-xs text-slate-500">Tindak lanjut sudah tuntas, serahkan kembali ke PTSP.</span>
                                    </span>
                                </button>
                                <div x-show="buka" x-cloak class="rounded-xl border border-emerald-100 bg-emerald-50/40 p-3">
                                    @if(!$permohonan->is_manual && $permohonan->layanan->perlu_dokumen_hasil)
                                        <p class="mb-2.5 text-xs text-slate-600">Layanan ini menghasilkan dokumen/surat resmi — unggah hasilnya di sini.</p>
                                        <form method="POST" action="{{ route('seksi.permohonan.selesai', $permohonan) }}" enctype="multipart/form-data">
                                            @csrf
                                            <label class="field-label">Nama Dokumen Hasil (opsional)</label>
                                            <input type="text" name="nama_dokumen" class="form-input" placeholder="Contoh: Surat Rekomendasi Bantuan Madrasah">
                                            <label class="field-label mt-2.5">Unggah Dokumen Hasil (opsional)</label>
                                            <input type="file" name="dokumen_hasil" class="form-input">
                                            <label class="field-label mt-2.5">Catatan Penyelesaian (opsional)</label>
                                            <input type="text" name="catatan" class="form-input">
                                            <button type="submit" class="primary-btn mt-3 w-full justify-center">
                                                Selesai — Kembalikan ke PTSP
                                            </button>
                                        </form>
                                    @else
                                        <p class="mb-2.5 text-xs text-slate-600">Layanan ini tidak memerlukan dokumen keluaran (misal: konsultasi/bimbingan) — cukup tandai selesai setelah pemohon dilayani langsung.</p>
                                        <form method="POST" action="{{ route('seksi.permohonan.selesai', $permohonan) }}">
                                            @csrf
                                            <label class="field-label">Catatan Penyelesaian (opsional)</label>
                                            <input type="text" name="catatan" class="form-input" placeholder="Contoh: Sudah dikonsultasikan langsung, pemohon paham solusinya">
                                            <button type="submit" class="primary-btn mt-3 w-full justify-center">
                                                Tandai Selesai — Kembalikan ke PTSP
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                    @else
                        <p class="mt-1 text-sm text-slate-600">Permohonan sudah tidak lagi di seksi ini (status: <x-status-badge :status="$permohonan->status" />).</p>
                    @endif
                </div>

                {{-- Mengisi ruang kosong di bawah kartu Aksi Seksi yang pendek, sekaligus
                     bantu petugas menilai permohonan mana yang sudah lama mengendap. --}}
                @if($permohonan->lama_di_status_ini)
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-xs text-slate-500">Lama di tahap ini</p>
                    <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $permohonan->lama_di_status_ini }}</p>
                </div>
                @endif
            </div>

            {{-- Timeline: baris sendiri, melintang 3 kolom, nempel di atas saat discroll. --}}
            <div class="lg:col-span-3 lg:row-start-1 lg:sticky lg:top-[4.75rem] lg:z-10 lg:self-start">
                <x-permohonan-timeline :permohonan="$permohonan" />
            </div>

            {{-- Kolom kiri: info, persyaratan, riwayat. --}}
            <div class="space-y-3 lg:col-span-2 lg:col-start-1 lg:row-start-2">

                <!-- Info Permohonan -->
                <div class="soft-card p-4 sm:p-5">
                    <div class="mb-4 flex items-start justify-between gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs text-slate-500">No. Tiket</p>
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
                            <dt class="text-xs text-slate-500">Pemohon</dt>
                            <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->user ? $permohonan->user->name : $permohonan->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">No. HP</dt>
                            <dd class="mt-0.5 font-medium text-slate-800">{{ ($permohonan->user->no_hp ?? null) ?: ($permohonan->no_hp ?: '-') }}</dd>
                        </div>
                        @unless($permohonan->is_manual)
                        <div>
                            <dt class="text-xs text-slate-500">Tipe Pelaksanaan</dt>
                            <dd class="mt-0.5 font-medium text-slate-800">{{ str_replace('_', ' ', $permohonan->layanan->tipe_pelaksanaan ?? '-') }}</dd>
                        </div>
                        @endunless
                        <div>
                            <dt class="text-xs text-slate-500">Tanggal Pengajuan</dt>
                            <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d F Y') }}</dd>
                        </div>
                        @if($permohonan->is_manual && $permohonan->deskripsi_layanan_manual)
                        <div class="sm:col-span-2">
                            <dt class="text-xs text-slate-500">Deskripsi Layanan</dt>
                            <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->deskripsi_layanan_manual }}</dd>
                        </div>
                        @endif
                        @if($permohonan->deskripsi)
                        <div class="sm:col-span-2">
                            <dt class="text-xs text-slate-500">Deskripsi</dt>
                            <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->deskripsi }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Persyaratan dari Pemohon: dropdown, terbuka default -->
                @if($permohonan->lampiranPermohonan->count() > 0 || $permohonan->is_manual)
                <details class="soft-card group" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                        <span class="text-[15px] font-semibold text-slate-900">
                            Persyaratan dari Pemohon
                            @unless($permohonan->is_manual)
                                <span class="ml-1 font-normal text-slate-500">({{ $permohonan->lampiranPermohonan->count() }})</span>
                            @endunless
                        </span>
                        <svg class="h-4 w-4 shrink-0 text-slate-500 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
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
    </div>
</x-app-layout>
