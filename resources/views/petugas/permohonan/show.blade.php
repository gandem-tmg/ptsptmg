<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600">Loket PTSP</p>
                <h2 class="mt-0.5 text-lg font-semibold text-slate-900">Detail Permohonan — {{ $permohonan->no_tiket }}</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('petugas.permohonan.index') }}" class="secondary-btn">Kembali</a>
                <button onclick="printToPosPrinter()" class="primary-btn">Cetak Bukti</button>
            </div>
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
                <p class="mt-0.5 text-xs text-slate-400">Seksi Penanggung Jawab: {{ $permohonan->seksi_label }}</p>
                @if($permohonan->is_manual && $permohonan->deskripsi_layanan_manual)
                    <p class="mt-1 text-sm text-slate-600">{{ $permohonan->deskripsi_layanan_manual }}</p>
                @endif

                <dl class="mt-4 grid grid-cols-1 gap-x-6 gap-y-3 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-slate-400">Pemohon</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->user ? $permohonan->user->name : $permohonan->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">No. HP</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ ($permohonan->user->no_hp ?? null) ?: ($permohonan->no_hp ?: '-') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Sumber Pengajuan</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->sumber_pengajuan === 'walk_in' ? 'Walk-in (loket)' : 'Online' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Tanggal Pengajuan</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d F Y') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-slate-400">Sedang di Seksi</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->lokasi_saat_ini }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Aksi PTSP: dipindah ke urutan atas & ditonjolkan, ini yang paling sering jadi tujuan petugas buka halaman ini -->
            <div class="soft-card border-l-4 border-l-emerald-500 p-4 sm:p-5">
                <h3 class="text-[15px] font-semibold text-slate-900">Aksi PTSP</h3>

                @if(in_array($permohonan->status, ['diajukan', 'verifikasi_ptsp']))
                    <p class="mt-1 text-sm text-slate-600 mb-3">Verifikasi kelengkapan berkas, lalu disposisikan ke seksi terkait atau kembalikan ke pemohon jika belum lengkap.</p>

                    <form method="POST" action="{{ route('petugas.permohonan.disposisikan', $permohonan) }}" class="mb-4 border-b border-slate-100 pb-4">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div>
                                <label for="seksi_id" class="field-label">Disposisikan ke Seksi</label>
                                <select name="seksi_id" id="seksi_id" class="form-input">
                                    <option value="">-- Default: {{ $permohonan->seksi_label !== '-' ? $permohonan->seksi_label : 'belum diatur' }} --</option>
                                    @foreach(\App\Models\Seksi::orderBy('nama_seksi')->get() as $seksi)
                                        <option value="{{ $seksi->id }}">{{ $seksi->nama_seksi }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-slate-500">Kosongkan untuk pakai seksi default layanan ini.</p>
                            </div>
                            <div>
                                <label for="catatan_disposisi" class="field-label">Catatan (opsional)</label>
                                <input type="text" name="catatan" id="catatan_disposisi" class="form-input">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="primary-btn">Disposisikan ke Seksi</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('petugas.permohonan.kembalikan', $permohonan) }}">
                        @csrf
                        @method('PATCH')
                        <label for="catatan_kembali" class="field-label">Alasan pengembalian</label>
                        <input type="text" name="catatan" id="catatan_kembali" class="form-input" required placeholder="Contoh: KTP belum diunggah">
                        <div class="mt-3">
                            <button type="submit" class="danger-btn">Kembalikan ke Pemohon</button>
                        </div>
                    </form>

                @elseif($permohonan->status === 'selesai_seksi')
                    <p class="mt-1 text-sm text-slate-600 mb-3">Seksi sudah menyelesaikan tindak lanjut. Verifikasi hasilnya sebelum diserahkan ke pemohon.</p>
                    <form method="POST" action="{{ route('petugas.permohonan.verifikasiAkhir', $permohonan) }}">
                        @csrf
                        @method('PATCH')
                        <label for="catatan_akhir" class="field-label">Catatan (opsional)</label>
                        <input type="text" name="catatan" id="catatan_akhir" class="form-input">
                        <div class="mt-3">
                            <button type="submit" class="primary-btn">Verifikasi &amp; Selesaikan</button>
                        </div>
                    </form>

                @elseif(in_array($permohonan->status, ['didisposisikan', 'diproses_seksi']))
                    <p class="mt-1 text-sm text-slate-600 mb-3">Sedang ditindaklanjuti oleh <strong>{{ $permohonan->currentSeksi->nama_seksi ?? '-' }}</strong>. Normalnya tidak ada aksi PTSP di tahap ini — menunggu seksi menyelesaikan lewat akunnya sendiri.</p>

                    <details class="rounded-lg border border-amber-200 bg-amber-50">
                        <summary class="cursor-pointer px-3.5 py-2.5 text-sm font-medium text-amber-800">
                            Seksi belum aktif pakai sistem? Update manual di sini
                        </summary>
                        <div class="px-3.5 pb-3.5">
                            <p class="text-xs text-amber-700 mb-2.5">
                                Hanya dipakai kalau seksi memang belum bisa masuk sistem sendiri. Wajib isi alasan — akan tercatat jelas di log sebagai update manual PTSP, bukan seolah-olah dari seksi.
                            </p>
                            <form method="POST" action="{{ route('petugas.permohonan.updateManual', $permohonan) }}">
                                @csrf
                                @method('PATCH')
                                <div class="grid grid-cols-1 gap-2.5 md:grid-cols-2">
                                    <select name="status" class="form-input text-sm" required>
                                        <option value="diproses_seksi" {{ $permohonan->status === 'diproses_seksi' ? 'disabled' : '' }}>Tandai: Diterima &amp; Diproses Seksi</option>
                                        <option value="selesai_seksi">Tandai: Selesai di Seksi</option>
                                    </select>
                                    <input type="text" name="catatan" placeholder="Alasan, contoh: info dari Seksi X via telepon" class="form-input text-sm" required minlength="5">
                                </div>
                                <button type="submit" class="mt-2.5 rounded-lg bg-amber-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                                    Update Manual
                                </button>
                            </form>
                        </div>
                    </details>

                @elseif($permohonan->status === 'selesai')
                    <p class="mt-1 text-sm text-emerald-700">Permohonan sudah selesai dan diserahkan ke pemohon.</p>

                @else
                    <p class="mt-1 text-sm text-slate-600">Tidak ada aksi tersedia untuk status ini.</p>
                @endif
            </div>

            <!-- Persyaratan dari Pemohon: dropdown, terbuka default karena jadi bahan pertimbangan aksi di atas -->
            <details class="soft-card group" open>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">
                        Persyaratan dari Pemohon
                        @if(!$permohonan->is_manual)
                            <span class="ml-1 font-normal text-slate-400">({{ $permohonan->lampiranPermohonan->count() }})</span>
                        @endif
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                    <x-lampiran-permohonan-list :permohonan="$permohonan" />
                </div>
            </details>

            <!-- Lampiran Hasil dari Seksi -->
            @if($permohonan->lampiranHasil->count() > 0)
            <details class="soft-card group" open>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                    <span class="text-[15px] font-semibold text-slate-900">Dokumen Hasil dari Seksi <span class="ml-1 font-normal text-slate-400">({{ $permohonan->lampiranHasil->count() }})</span></span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="border-t border-slate-100 p-4 pt-3 sm:p-5 sm:pt-3">
                    <x-lampiran-hasil-list :permohonan="$permohonan" />
                </div>
            </details>
            @endif

            <!-- Riwayat & Log: dropdown, tertutup default — riwayat audit, jarang jadi acuan aksi harian -->
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
                                    @if($d->tanggal_diterima) &middot; Diterima {{ $d->tanggal_diterima->format('d/m/Y H:i') }} @endif
                                    @if($d->tanggal_selesai_seksi) &middot; Selesai {{ $d->tanggal_selesai_seksi->format('d/m/Y H:i') }} @endif
                                </p>
                                @if($d->catatan)<p class="text-sm text-slate-600 mt-1">{{ $d->catatan }}</p>@endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div>
                        <h4 class="mb-2.5 text-sm font-semibold text-slate-700">Log Riwayat Status</h4>
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
                </div>
            </details>

        </div>
    </div>

    <script>
        function printToPosPrinter() {
            const pdfUrl = '{{ route("petugas.permohonan.pdf", $permohonan) }}';
            const printWindow = window.open(pdfUrl, '_blank');
            printWindow.onload = function () { printWindow.print(); };
        }
    </script>
</x-app-layout>
