<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600">Loket PTSP</p>
                <h2 class="mt-0.5 text-lg font-semibold text-slate-900">Detail Permohonan — {{ $permohonan->no_tiket }}</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('petugas.permohonan.index') }}" class="secondary-btn">Kembali</a>
                {{-- Sekunder: ini dokumentasi, bukan keputusan — aksi utama ada di kartu Aksi PTSP. --}}
                <button onclick="printToPosPrinter()" class="secondary-btn">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659" /></svg>
                    Cetak Bukti
                </button>
            </div>
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
            Layout dashboard: timeline full-width nempel di atas (baris sendiri,
            melintang 3 kolom), lalu di bawahnya 2 kolom — kiri konten, kanan aksi
            (mirip kartu-kartu di Dasbor). Satu kolom ditumpuk di HP.

            Urutan SUMBER di bawah ini sengaja: Aksi PTSP dulu, baru Timeline, baru
            konten kiri — supaya di HP (grid-cols-1, tanpa row/col override) urutan
            tampil jadi Aksi PTSP paling atas (tidak perlu scroll dulu), baru Timeline,
            baru detail. Di layar lg ke atas, class lg:row-start-*/lg:col-start-* di
            bawah yang mengatur ulang posisi visualnya (Timeline naik ke baris 1 penuh
            lebar, Aksi PTSP pindah ke kolom kanan baris 2) — jadi urutan sumber & urutan
            tampil tidak perlu sama-sama diubah manual dua kali untuk tiap breakpoint.
        --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            @php
                $isPilihanGanda = in_array($permohonan->status, ['diajukan', 'verifikasi_ptsp']);
            @endphp

            {{--
                Sidebar kanan: Aksi PTSP + info "lama di tahap ini" (mengisi ruang
                kosong di bawah kartu aksi yang pendek). Top offset lebih besar dari
                timeline (9.5rem vs 4.75rem) supaya dia nempel PAS DI BAWAH timeline
                yang juga sticky, bukan numpuk di atasnya — ini angka perkiraan dari
                tinggi timeline versi lg (full-width, label 1 baris); kalau di
                layarmu ternyata masih ada celah atau malah sedikit tumpang tindih,
                kabari saja, tinggal 1 angka ini yang disesuaikan.
            --}}
            <div class="space-y-3 lg:col-start-3 lg:row-start-2 lg:sticky lg:top-[9.5rem] lg:self-start">
                <div class="soft-card border-l-4 border-l-emerald-500 p-4 sm:p-5">
                    <h3 class="text-[15px] font-semibold text-slate-900">Aksi PTSP</h3>

                    @if($isPilihanGanda)
                        <p class="mt-1 mb-3 text-sm text-slate-600">Pilih salah satu tindak lanjut untuk permohonan ini.</p>

                        <div x-data="{ pilih: null }" class="space-y-2.5">
                            {{-- Kartu pilihan: Disposisikan ke Seksi --}}
                            <button type="button" @click="pilih = (pilih === 'disposisi' ? null : 'disposisi')"
                                class="flex w-full items-start gap-3 rounded-xl border bg-white p-3.5 text-left transition"
                                :class="pilih === 'disposisi' ? 'border-emerald-500 ring-1 ring-emerald-500' : 'border-slate-200 hover:border-emerald-300'">
                                <span class="inline-flex shrink-0 rounded-lg bg-emerald-50 p-2 text-emerald-600">
                                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a1 1 0 00-.9.55l-.7 1.4a1 1 0 01-.9.55h-3a1 1 0 01-.9-.55l-.7-1.4a1 1 0 00-.9-.55H4" /></svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-slate-900">Disposisikan ke Seksi</span>
                                    <span class="mt-0.5 block text-xs text-slate-500">Berkas lengkap, teruskan ke seksi terkait.</span>
                                </span>
                            </button>
                            <form x-show="pilih === 'disposisi'" x-cloak method="POST" action="{{ route('petugas.permohonan.disposisikan', $permohonan) }}"
                                class="rounded-xl border border-emerald-100 bg-emerald-50/40 p-3">
                                @csrf
                                @method('PATCH')
                                <label for="seksi_id" class="field-label">Disposisikan ke Seksi</label>
                                <select name="seksi_id" id="seksi_id" class="form-input">
                                    <option value="">-- Default: {{ $permohonan->seksi_label !== '-' ? $permohonan->seksi_label : 'belum diatur' }} --</option>
                                    @foreach(\App\Models\Seksi::orderBy('nama_seksi')->get() as $seksi)
                                        <option value="{{ $seksi->id }}">{{ $seksi->nama_seksi }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-slate-500">Kosongkan untuk pakai seksi default layanan ini.</p>
                                <label for="catatan_disposisi" class="field-label mt-2.5">Catatan (opsional)</label>
                                <input type="text" name="catatan" id="catatan_disposisi" class="form-input">
                                <button type="submit" class="primary-btn mt-3 w-full justify-center">Disposisikan ke Seksi</button>
                            </form>

                            {{-- Kartu pilihan: Kembalikan ke Pemohon --}}
                            <button type="button" @click="pilih = (pilih === 'kembalikan' ? null : 'kembalikan')"
                                class="flex w-full items-start gap-3 rounded-xl border bg-white p-3.5 text-left transition"
                                :class="pilih === 'kembalikan' ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-200 hover:border-rose-300'">
                                <span class="inline-flex shrink-0 rounded-lg bg-rose-50 p-2 text-rose-600">
                                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-slate-900">Kembalikan ke Pemohon</span>
                                    <span class="mt-0.5 block text-xs text-slate-500">Berkas belum lengkap / tidak sesuai syarat.</span>
                                </span>
                            </button>
                            <form x-show="pilih === 'kembalikan'" x-cloak method="POST" action="{{ route('petugas.permohonan.kembalikan', $permohonan) }}"
                                class="rounded-xl border border-rose-100 bg-rose-50/40 p-3">
                                @csrf
                                @method('PATCH')
                                <label for="catatan_kembali" class="field-label">Alasan pengembalian</label>
                                <input type="text" name="catatan" id="catatan_kembali" class="form-input" required placeholder="Contoh: KTP belum diunggah">
                                <button type="submit" class="danger-btn mt-3 w-full justify-center">Kembalikan ke Pemohon</button>
                            </form>
                        </div>

                    @elseif($permohonan->status === 'selesai_seksi')
                        <div class="mt-2 flex items-start gap-3 rounded-xl bg-sky-50 p-3">
                            <span class="inline-flex shrink-0 rounded-lg bg-sky-100 p-2 text-sky-600">
                                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </span>
                            <span class="text-sm text-sky-900">Seksi sudah menyelesaikan tindak lanjut. Verifikasi hasilnya sebelum diserahkan ke pemohon.</span>
                        </div>
                        <form method="POST" action="{{ route('petugas.permohonan.verifikasiAkhir', $permohonan) }}" class="mt-3">
                            @csrf
                            @method('PATCH')
                            <label for="catatan_akhir" class="field-label">Catatan (opsional)</label>
                            <input type="text" name="catatan" id="catatan_akhir" class="form-input">
                            <button type="submit" class="primary-btn mt-3 w-full justify-center">Verifikasi &amp; Selesaikan</button>
                        </form>

                    @elseif(in_array($permohonan->status, ['didisposisikan', 'diproses_seksi']))
                        <div class="mt-2 flex items-start gap-3 rounded-xl bg-slate-50 p-3">
                            <span class="inline-flex shrink-0 rounded-lg bg-sky-50 p-2 text-sky-600">
                                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </span>
                            <span class="text-sm text-slate-600">Sedang ditindaklanjuti oleh<br><strong class="text-slate-900">{{ $permohonan->currentSeksi->nama_seksi ?? '-' }}</strong></span>
                        </div>

                        {{--
                            Dulu disembunyikan di <details> kecil bernada "cuma kalau kepepet".
                            Untuk kondisi sekarang (semua seksi belum proaktif pakai sistem),
                            ini justru jalur utama — jadi dipromosikan jadi kartu biasa yang
                            langsung kelihatan, bukan accordion yang gampang terlewat.
                        --}}
                        {{-- Default terbuka: beda dari pilihan Disposisikan/Kembalikan di atas
                             (dua opsi, wajar user pilih dulu), di tahap ini cuma ada SATU aksi
                             yang mungkin — jadi tidak perlu klik dulu untuk membukanya. Kartu
                             tetap bisa diklik untuk ditutup kalau mau. --}}
                        <div class="mt-3" x-data="{ buka: true }">
                            <button type="button" @click="buka = !buka"
                                class="flex w-full items-start gap-3 rounded-xl border bg-white p-3.5 text-left transition"
                                :class="buka ? 'border-amber-400 ring-1 ring-amber-400' : 'border-slate-200 hover:border-amber-300'">
                                <span class="inline-flex shrink-0 rounded-lg bg-amber-50 p-2 text-amber-600">
                                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-slate-900">Update Manual</span>
                                    <span class="mt-0.5 block text-xs text-slate-500">Seksi belum aktif pakai sistem — PTSP update atas nama seksi.</span>
                                </span>
                            </button>
                            <div x-show="buka" x-cloak class="rounded-xl border border-amber-100 bg-amber-50/40 p-3">
                                <p class="text-xs text-amber-700 mb-2.5">
                                    Wajib isi alasan — akan tercatat jelas di log sebagai update manual PTSP, bukan seolah-olah dari seksi.
                                </p>
                                <form method="POST" action="{{ route('petugas.permohonan.updateManual', $permohonan) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-input text-sm" required>
                                        <option value="diproses_seksi" {{ $permohonan->status === 'diproses_seksi' ? 'disabled' : '' }}>Tandai: Diterima &amp; Diproses Seksi</option>
                                        <option value="selesai_seksi">Tandai: Selesai di Seksi</option>
                                    </select>
                                    <input type="text" name="catatan" placeholder="Alasan, contoh: info dari Seksi X via telepon" class="form-input text-sm mt-2.5" required minlength="5">
                                    <button type="submit" class="mt-2.5 w-full justify-center rounded-lg bg-amber-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                                        Update Manual
                                    </button>
                                </form>
                            </div>
                        </div>

                    @elseif($permohonan->status === 'selesai')
                        <p class="mt-1 text-sm text-emerald-700">Permohonan sudah selesai dan diserahkan ke pemohon.</p>

                    @else
                        <p class="mt-1 text-sm text-slate-600">Tidak ada aksi tersedia untuk status ini.</p>
                    @endif
                </div>

                {{-- Mengisi ruang kosong di bawah kartu Aksi PTSP yang pendek, sekaligus
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

            {{-- Kolom kiri: detail, dokumen, riwayat — urutan sumber ketiga,
                 tapi secara visual ditempatkan di kolom 1-2 baris 2. --}}
            <div class="space-y-3 lg:col-span-2 lg:col-start-1 lg:row-start-2">

                @php
                    $butuhAksiCepat = in_array($permohonan->status, ['diajukan', 'verifikasi_ptsp', 'selesai_seksi']);
                @endphp

                {{--
                    Info Permohonan + Persyaratan digabung jadi satu accordion "Detail Lengkap
                    Permohonan". Tertutup default saat status masih butuh aksi cepat (petugas
                    bisa buka kalau perlu cek detail, tapi tidak dipaksa scroll melewatinya
                    untuk sampai ke bagian aksi) — terbuka default kalau memang tidak ada
                    keputusan yang harus segera diambil.
                --}}
                <details class="soft-card group" @if(!$butuhAksiCepat) open @endif>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 sm:p-5">
                        <span class="text-[15px] font-semibold text-slate-900">Detail Lengkap Permohonan</span>
                        <svg class="h-4 w-4 shrink-0 text-slate-500 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <div class="border-t border-slate-100 p-4 pt-4 sm:p-5 sm:pt-4">
                        <!-- Info Permohonan -->
                        <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-4">
                            <div>
                                <p class="text-xs text-slate-500">No. Tiket</p>
                                <p class="mt-0.5 font-mono text-base font-semibold text-slate-900">{{ $permohonan->no_tiket }}</p>
                            </div>
                            <x-status-badge :status="$permohonan->status" class="shrink-0" />
                        </div>

                        <h3 class="mt-4 text-[15px] font-semibold text-slate-900">
                            {{ $permohonan->nama_layanan_label }}
                            @if($permohonan->is_manual)
                                <span class="ml-1 rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Layanan di luar katalog</span>
                            @endif
                        </h3>
                        <p class="mt-0.5 text-xs text-slate-500">Seksi Penanggung Jawab: {{ $permohonan->seksi_label }}</p>
                        @if($permohonan->is_manual && $permohonan->deskripsi_layanan_manual)
                            <p class="mt-1 text-sm text-slate-600">{{ $permohonan->deskripsi_layanan_manual }}</p>
                        @endif

                        <dl class="mt-4 grid grid-cols-1 gap-x-6 gap-y-3 border-b border-slate-100 pb-5 text-sm sm:grid-cols-2">
                            <div>
                                <dt class="text-xs text-slate-500">Pemohon</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->user ? $permohonan->user->name : $permohonan->nama }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">No. HP</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ ($permohonan->user->no_hp ?? null) ?: ($permohonan->no_hp ?: '-') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Sumber Pengajuan</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->sumber_pengajuan === 'walk_in' ? 'Walk-in (loket)' : 'Online' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Tanggal Pengajuan</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d F Y') }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs text-slate-500">Sedang di Seksi</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ $permohonan->lokasi_saat_ini }}</dd>
                            </div>
                        </dl>

                        <!-- Persyaratan dari Pemohon -->
                        <h4 class="mt-5 text-sm font-semibold text-slate-900">
                            Persyaratan dari Pemohon
                            @if(!$permohonan->is_manual)
                                <span class="ml-1 font-normal text-slate-500">({{ $permohonan->lampiranPermohonan->count() }})</span>
                            @endif
                        </h4>
                        <div class="mt-3">
                            <x-lampiran-permohonan-list :permohonan="$permohonan" />
                        </div>
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
    </div>

    <script>
        function printToPosPrinter() {
            const pdfUrl = '{{ route("petugas.permohonan.pdf", $permohonan) }}';
            const printWindow = window.open(pdfUrl, '_blank');
            printWindow.onload = function () { printWindow.print(); };
        }
    </script>
</x-app-layout>
