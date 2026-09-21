<div class="relative overflow-hidden" x-data="{ wizardOpen: false }">
    @guest
    <div class="absolute inset-x-0 top-0 -z-10 h-72 bg-gradient-to-br from-emerald-100/70 via-white to-emerald-50/50 blur-3xl"></div>
    @endguest

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 {{ auth()->check() ? '' : 'lg:py-14' }}">
        <!-- Hero -->
        <div class="mb-8">
            @guest
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-5 lg:items-center">
                <div class="lg:col-span-3">
                    <div class="mb-4 inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">
                        PTSP Online
                    </div>
                    <h1 class="max-w-2xl text-3xl font-bold text-slate-900 sm:text-4xl">
                        Layanan Terpadu Satu Pintu
                        <span class="block text-2xl font-semibold text-slate-600 sm:text-3xl">Kementerian Agama Kabupaten Temanggung</span>
                    </h1>
                    <p class="mt-3 max-w-2xl text-base text-slate-600">
                        Apa yang ingin Anda urus hari ini? Pilih kategori sesuai kebutuhan Anda, atau langsung cari nama layanannya.
                    </p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="#daftar-layanan" class="primary-btn text-center">Ajukan Permohonan</a>
                        <a href="{{ route('guest.searchTicket') }}" class="secondary-btn text-center">Cek Status Permohonan</a>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="rounded-3xl bg-gradient-to-br from-emerald-600 to-teal-500 p-6 text-white shadow-lg shadow-emerald-100 sm:p-7">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-50">Panduan Pengajuan Permohonan</p>
                        <ol class="mt-5 space-y-4">
                            <li class="flex items-start gap-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white/20 text-sm font-bold">1</span>
                                <div>
                                    <p class="text-sm font-semibold">Pilih Jenis Layanan</p>
                                    <p class="text-xs text-emerald-50">Telusuri layanan sesuai kebutuhan Anda.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white/20 text-sm font-bold">2</span>
                                <div>
                                    <p class="text-sm font-semibold">Isi Data Pemohon</p>
                                    <p class="text-xs text-emerald-50">Lengkapi identitas Anda pada formulir pengajuan.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white/20 text-sm font-bold">3</span>
                                <div>
                                    <p class="text-sm font-semibold">Unggah Dokumen Persyaratan</p>
                                    <p class="text-xs text-emerald-50">Sesuai ketentuan pada masing-masing layanan.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white/20 text-sm font-bold">4</span>
                                <div>
                                    <p class="text-sm font-semibold">Pantau Status Permohonan</p>
                                    <p class="text-xs text-emerald-50">Cek perkembangan kapan saja lewat akun Anda.</p>
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            @else
                <h1 class="text-xl font-semibold text-slate-900 sm:text-2xl">Pilih Layanan</h1>
                <p class="mt-1 text-sm text-slate-500">Pilih kategori sesuai kebutuhan, lalu pilih layanan yang ingin diajukan.</p>
            @endguest
        </div>

        <!-- Pencarian & Filter -->
        <div id="daftar-layanan" class="soft-card mb-6 scroll-mt-20 p-4 sm:p-6">
            <form method="GET" action="{{ route('layanan.index') }}" class="flex flex-col gap-3 sm:flex-row">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan... (contoh: izin operasional madrasah, bantuan masjid, legalisir)"
                       class="flex-1 rounded-xl border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-400 focus:ring-emerald-400">
                <select name="kategori" class="rounded-xl border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-400 focus:ring-emerald-400">
                    <option value="">Semua Kategori</option>
                    @foreach(config('klasifikasi_layanan.kategori') as $slug => $def)
                        <option value="{{ $slug }}" {{ request('kategori') == $slug ? 'selected' : '' }}>{{ $def['label'] }}</option>
                    @endforeach
                </select>
                <button type="submit" class="primary-btn">Cari</button>
                @if($isFiltering)
                    <a href="{{ route('layanan.index') }}" class="secondary-btn text-center">Reset</a>
                @endif
            </form>
            @unless($isFiltering)
            <p class="mt-3 text-xs text-slate-400">{{ $totalLayanan }} layanan tersedia, dikelompokkan dalam {{ $kategoris->count() }} kategori kebutuhan.</p>
            @endunless
        </div>

        <!-- Wizard "Tidak tahu harus pilih layanan apa?" -->
        <div class="mb-8">
            @if(!$personaAktif)
            <button type="button" @click="wizardOpen = !wizardOpen"
                    class="soft-card flex w-full items-center justify-between gap-4 border-2 border-dashed border-emerald-200 bg-emerald-50/60 p-5 text-left transition hover:bg-emerald-50">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="font-semibold text-slate-900">Tidak tahu harus pilih layanan apa?</p>
                        <p class="text-sm text-slate-500">Bantu saya menemukan layanan — cukup pilih Anda ini siapa.</p>
                    </div>
                </div>
                <svg class="h-5 w-5 shrink-0 text-emerald-600 transition-transform duration-200" :class="wizardOpen ? 'rotate-180' : ''"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>

            <div x-show="wizardOpen" x-cloak
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 class="soft-card mt-3 border border-emerald-100 p-5">
                <p class="mb-4 text-sm font-medium text-slate-700">Anda ini siapa / posisi Anda saat ini?</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($personaConfig as $slug => $def)
                    <a href="{{ route('layanan.index', ['untuk' => $slug]) }}"
                       class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700">
                        {{ $def['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            <div class="soft-card flex flex-wrap items-center justify-between gap-3 border border-emerald-200 bg-emerald-50/60 p-4">
                <p class="text-sm text-slate-700">
                    Menampilkan layanan untuk:
                    <span class="font-semibold text-emerald-700">{{ $personaConfig[$personaAktif]['label'] ?? $personaAktif }}</span>
                </p>
                <a href="{{ route('layanan.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Ganti / lihat semua</a>
            </div>
            @endif
        </div>

        @if(!$isFiltering && $layananPopuler->isNotEmpty())
        <div class="mb-8">
            <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-900">
                <svg class="h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                Layanan Populer
            </h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($layananPopuler as $layanan)
                    <x-layanan-mini-card :layanan="$layanan" />
                @endforeach
            </div>
        </div>
        @endif

        @if($isFiltering)
            @if($searchMatchesPengaduan && $layananPengaduan)
            <div class="soft-card mb-6 flex flex-wrap items-center justify-between gap-3 border border-amber-200 bg-amber-50 p-4">
                <p class="text-sm text-amber-800">
                    Sepertinya yang Anda cari adalah layanan <strong>Pengaduan</strong> — ini bukan bagian dari kategori kebutuhan, tapi bisa langsung diakses lewat tombol berikut.
                </p>
                <a href="{{ route('layanan.show', $layananPengaduan) }}" class="secondary-btn shrink-0 text-center">Buka Halaman Pengaduan</a>
            </div>
            @endif
            <!-- Mode hasil pencarian/wizard/filter kategori: langsung tampilkan semua yang cocok -->
            @forelse($kategoris as $kategori)
            <div class="mb-10">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">
                    {{ $kategori->label }}
                </h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($kategori->layananList as $layanan)
                        <x-layanan-mini-card :layanan="$layanan" />
                    @endforeach
                </div>
            </div>
            @empty
            <div class="soft-card p-6 text-center text-slate-500">
                Tidak ada layanan yang cocok. Coba kata kunci lain, atau
                <a href="{{ route('layanan.index') }}" class="font-medium text-emerald-700 hover:underline">lihat semua layanan</a>.
            </div>
            @endforelse

        @else
            <!-- Mode browse: tampilkan kategori kebutuhan dulu, klik untuk buka daftar layanannya -->
            {{-- Kategori mana yang lagi kebuka disimpan di ?buka= lewat
                 history.replaceState (tanpa reload) tiap kali di-toggle,
                 dan dibaca lagi sebagai initial state dari server
                 ($bukaKategori). Efeknya: browser-back atau link "Kembali
                 ke daftar layanan" dari halaman detail balik dengan
                 accordion kategori yang relevan sudah kebuka lagi. --}}
            <div class="space-y-3" x-data="{ openKategori: @js($bukaKategori) }" x-init="
                $watch('openKategori', (slug) => {
                    const url = new URL(window.location.href);
                    if (slug) { url.searchParams.set('buka', slug); } else { url.searchParams.delete('buka'); }
                    window.history.replaceState(window.history.state, '', url.toString());
                })
            ">
                @foreach($kategoris as $kategori)
                <div class="soft-card overflow-hidden">
                    <button type="button" @click="openKategori = (openKategori === '{{ $kategori->slug }}') ? null : '{{ $kategori->slug }}'"
                            class="flex w-full items-center justify-between gap-4 p-5 text-left transition hover:bg-emerald-50/40">
                        <div class="flex min-w-0 items-start gap-3">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-slate-900">{{ $kategori->label }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $kategori->deskripsi }}</p>
                                <p class="mt-1.5 text-xs font-medium text-emerald-700">{{ $kategori->layananList->count() }} layanan</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform duration-200"
                             :class="openKategori === '{{ $kategori->slug }}' ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openKategori === '{{ $kategori->slug }}'" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="border-t border-slate-100 bg-slate-50/50 p-5">
                        @if($kategori->layananList->isEmpty())
                            <p class="text-sm text-slate-400">Belum ada layanan terdaftar di kategori ini.</p>
                        @else
                            @if(count($kategori->subkategori))
                                {{-- Kategori dengan subkategori (mis. Pendidikan) -> dikelompokkan lagi jadi tab kecil --}}
                                @foreach($kategori->subkategori as $subSlug => $subLabel)
                                    @php $subList = $kategori->layananList->where('subkategori', $subSlug)->values(); @endphp
                                    @if($subList->isNotEmpty())
                                    <div class="mb-5 last:mb-0">
                                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $subLabel }}</p>
                                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                            @foreach($subList as $layanan)
                                                <x-layanan-mini-card :layanan="$layanan" />
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                                @php $tanpaSub = $kategori->layananList->whereNull('subkategori')->values(); @endphp
                                @if($tanpaSub->isNotEmpty())
                                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach($tanpaSub as $layanan)
                                        <x-layanan-mini-card :layanan="$layanan" />
                                    @endforeach
                                </div>
                                @endif
                            @else
                                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach($kategori->layananList as $layanan)
                                        <x-layanan-mini-card :layanan="$layanan" />
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        <!-- Pengaduan: selalu terlihat, di luar grid kategori (lintas-domain, bukan "kebutuhan" yang sejajar) -->
        @if($layananPengaduan)
        <div class="soft-card mt-10 flex flex-col items-start justify-between gap-3 border border-slate-100 bg-slate-50/60 p-5 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3">
                <div>
                    <p class="font-semibold text-slate-900">Ada keluhan atau pengaduan?</p>
                    <p class="text-sm text-slate-500">Sampaikan pengaduan terkait pelayanan publik Kemenag Kab. Temanggung di sini.</p>
                </div>
            </div>
            <a href="{{ route('layanan.show', $layananPengaduan) }}" class="secondary-btn shrink-0 text-center">Sampaikan Pengaduan</a>
        </div>
        @endif
    </div>
</div>
