<div class="mx-auto max-w-5xl {{ auth()->check() ? '' : 'px-4 py-10 sm:px-6 lg:px-8 lg:py-14' }}">

    <a href="{{ route('layanan.index', $layanan->kategori ? ['buka' => $layanan->kategori] : []) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-700 hover:text-emerald-800">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali ke daftar layanan
    </a>

    @if($layanan->kategori)
    <nav class="mt-3 flex items-center gap-1.5 text-xs text-slate-500">
        <a href="{{ route('layanan.index') }}" class="hover:text-emerald-700">Layanan</a>
        <span>/</span>
        <a href="{{ route('layanan.index', ['kategori' => $layanan->kategori]) }}" class="hover:text-emerald-700">{{ $layanan->kategori_label }}</a>
    </nav>
    @endif

    <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Konten utama -->
        <div class="soft-card p-5 sm:p-6 lg:col-span-2">
            <div class="flex flex-wrap items-center gap-2">
                @if($layanan->tipe_pelaksanaan)
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide
                    @if($layanan->tipe_pelaksanaan == 'full_digital') bg-green-100 text-green-700
                    @elseif($layanan->tipe_pelaksanaan == 'perlu_fisik') bg-amber-100 text-amber-700
                    @else bg-sky-100 text-sky-700 @endif">
                    @if($layanan->tipe_pelaksanaan == 'full_digital') Full Online
                    @elseif($layanan->tipe_pelaksanaan == 'perlu_fisik') Perlu Hadir Langsung
                    @else Via Sistem Nasional @endif
                </span>
                @endif
                @if($layanan->jenis_layanan_label)
                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                    {{ $layanan->jenis_layanan_label }}
                </span>
                @endif
            </div>

            <h1 class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ $layanan->nama_layanan }}</h1>

            {{-- Seksi/unit tetap ditampilkan untuk transparansi, tapi hanya sebagai
                 info — bukan elemen navigasi (lihat kesepakatan desain arsitektur). --}}
            <div class="mt-3 flex items-center gap-2 text-sm text-slate-600">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-3.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" /></svg>
                Ditangani oleh <span class="font-medium text-slate-700">{{ $layanan->seksi->nama_seksi ?? '-' }}</span>
            </div>

            @if($layanan->deskripsi)
            <p class="mt-5 leading-relaxed text-slate-700">{{ $layanan->deskripsi }}</p>
            @endif

            @if($layanan->tipe_pelaksanaan === 'perlu_fisik')
            <div class="mt-5 flex gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Layanan ini memerlukan kehadiran langsung dan/atau dokumen asli pada salah satu tahapnya. Anda tetap bisa mengajukan &amp; upload berkas dari sini — proses dan statusnya tetap bisa dipantau di sistem.</span>
            </div>
            @elseif($layanan->tipe_pelaksanaan === 'sistem_eksternal')
            <div class="mt-5 flex gap-3 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Pengerjaan teknis layanan ini menggunakan aplikasi resmi Kementerian Agama. Pengajuan tetap dilakukan lewat PTSP dan progresnya tetap bisa Anda pantau di sini.</span>
            </div>
            @endif

            <div class="mt-8 space-y-2.5" x-data="{ open: { persyaratan: true, mekanisme: false, jangka_waktu: false, biaya: false, produk: false } }">

                <!-- 1. Persyaratan yang perlu disiapkan (default terbuka) -->
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <button type="button" @click="open.persyaratan = !open.persyaratan"
                            class="flex w-full items-center justify-between gap-3 bg-slate-50 px-4 py-3 text-left transition hover:bg-slate-100">
                        <span class="flex items-center gap-2 text-base font-semibold text-slate-900">
                            Persyaratan yang perlu disiapkan
                            @if($layanan->persyaratan->where('wajib', true)->count() > 0)
                            <span class="text-xs font-normal text-slate-500"><span class="required-mark">*</span> wajib</span>
                            @endif
                        </span>
                        <svg class="h-4 w-4 shrink-0 text-slate-500 transition-transform duration-200" :class="open.persyaratan ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open.persyaratan" x-cloak
                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                         class="border-t border-slate-100 px-4 py-3">
                        @if($layanan->persyaratan->count() > 0)
                        <ul class="space-y-2">
                            @foreach($layanan->persyaratan as $persyaratan)
                            <li class="flex items-start gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $persyaratan->nama_persyaratan }}</span>
                                @if($persyaratan->wajib)
                                    <span class="required-mark">*</span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-slate-600">Belum ada data persyaratan untuk layanan ini.</p>
                        @endif
                    </div>
                </div>

                <!-- 2-5. Poin standar pelayanan lain — masing-masing independen, tidak saling menutup -->
                @php
                    $poinStandar = [
                        'mekanisme' => ['label' => 'Sistem, Mekanisme, dan Prosedur', 'isi' => $layanan->sistem_mekanisme_prosedur],
                        'jangka_waktu' => ['label' => 'Jangka Waktu Pelayanan', 'isi' => $layanan->jangka_waktu_pelayanan],
                        'biaya' => ['label' => 'Biaya / Tarif', 'isi' => $layanan->biaya_tarif],
                        'produk' => ['label' => 'Produk Pelayanan', 'isi' => $layanan->produk_pelayanan],
                    ];
                @endphp
                @foreach($poinStandar as $key => $poin)
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <button type="button" @click="open.{{ $key }} = !open.{{ $key }}"
                            class="flex w-full items-center justify-between gap-3 bg-slate-50 px-4 py-3 text-left transition hover:bg-slate-100">
                        <span class="text-base font-semibold text-slate-900">{{ $poin['label'] }}</span>
                        <svg class="h-4 w-4 shrink-0 text-slate-500 transition-transform duration-200" :class="open.{{ $key }} ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open.{{ $key }}" x-cloak
                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                         class="border-t border-slate-100 px-4 py-3 text-sm leading-relaxed text-slate-600">
                        @if($poin['isi'])
                            {!! nl2br(e($poin['isi'])) !!}
                        @else
                            <p class="text-slate-500">Data belum tersedia untuk poin ini.</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- CTA sidebar -->
        <div class="lg:col-span-1">
            <div class="soft-card sticky top-6 p-6">
                <p class="text-sm text-slate-600">Siap mengajukan?</p>
                <p class="mt-1 text-sm text-slate-600">Masuk diperlukan supaya Anda bisa memantau status pengajuan kapan saja dari akun Anda.</p>
                <a href="{{ route('layanan.ajukan', $layanan) }}" class="primary-btn mt-4 block text-center">
                    Ajukan Sekarang
                </a>

                <dl class="mt-6 space-y-3 border-t border-slate-100 pt-5 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-600">Jumlah persyaratan</dt>
                        <dd class="font-medium text-slate-900">{{ $layanan->persyaratan->count() }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-600">Unit terkait</dt>
                        <dd class="text-right font-medium text-slate-900">{{ $layanan->seksi->nama_seksi ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
