<x-public-layout>
    <div class="relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 -z-10 h-72 bg-gradient-to-r from-emerald-200/40 via-white to-cyan-100/40 blur-3xl"></div>

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-16">
            <div class="soft-card overflow-hidden p-5 sm:p-8 lg:p-10">
                <div class="grid items-center gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                    <div>
                        <div class="mb-4 inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">
                            PTSP Online
                        </div>
                        <h1 class="max-w-xl text-3xl font-bold text-slate-900 sm:text-4xl lg:text-5xl">
                            Layanan pengajuan dan status permohonan yang lebih cepat dan modern.
                        </h1>
                        <p class="mt-4 max-w-xl text-base text-slate-600 sm:text-lg">
                            Kantor Kementerian Agama Kabupaten Temanggung menghadirkan proses layanan yang mudah diakses, aman, dan ramah di perangkat mobile.
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('guest.permohonan.biodata') }}" class="primary-btn w-full sm:w-auto">
                                Ajukan Permohonan
                            </a>
                            <a href="{{ route('guest.searchTicket') }}" class="secondary-btn w-full sm:w-auto">
                                Cek Status
                            </a>
                        </div>

                        <div class="mt-8 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-emerald-600">24/7</div>
                                <div class="mt-1 text-sm text-slate-600">Akses layanan</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-sky-600">Mobile</div>
                                <div class="mt-1 text-sm text-slate-600">Responsive UI</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-violet-600">1x</div>
                                <div class="mt-1 text-sm text-slate-600">Proses lebih praktis</div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[28px] bg-gradient-to-br from-emerald-600 via-emerald-500 to-cyan-500 p-5 text-white shadow-xl shadow-emerald-300/40 sm:p-6">
                        <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                            <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-100">Langkah cepat</p>
                            <div class="mt-5 space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-sm font-bold">1</div>
                                    <div>
                                        <h3 class="text-base font-semibold">Isi biodata</h3>
                                        <p class="text-sm text-emerald-50/90">Masukkan data pemohon secara lengkap.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-sm font-bold">2</div>
                                    <div>
                                        <h3 class="text-base font-semibold">Upload dokumen</h3>
                                        <p class="text-sm text-emerald-50/90">Lengkapi persyaratan yang dibutuhkan.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-sm font-bold">3</div>
                                    <div>
                                        <h3 class="text-base font-semibold">Pantau status</h3>
                                        <p class="text-sm text-emerald-50/90">Cek nomor tiket kapan saja dari perangkat Anda.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 md:grid-cols-3">
                <div class="soft-card p-6">
                    <div class="mb-4 inline-flex rounded-xl bg-emerald-100 p-3 text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 113 3L12 16l-4 1 1-4 7.5-7.5z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900">Ajukan Permohonan</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Login dengan akun Anda atau daftar tanpa akun untuk mengajukan permohonan baru dengan mudah.</p>
                    <a href="{{ route('login') }}" class="mt-5 inline-flex text-sm font-semibold text-emerald-600 hover:text-emerald-700">Login & Ajukan →</a>
                </div>

                <div class="soft-card p-6">
                    <div class="mb-4 inline-flex rounded-xl bg-sky-100 p-3 text-sky-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16V4m0 0L4 8m4-4l4 4m-4 8v4m8-16h4a2 2 0 012 2v11a2 2 0 01-2 2h-4m-8 0h8"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900">Ajukan tanpa akun</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Isi biodata dan unggah dokumen persyaratan tanpa harus login terlebih dahulu.</p>
                    <a href="{{ route('guest.permohonan.biodata') }}" class="mt-5 inline-flex text-sm font-semibold text-sky-600 hover:text-sky-700">Mulai sekarang →</a>
                </div>

                <div class="soft-card p-6">
                    <div class="mb-4 inline-flex rounded-xl bg-violet-100 p-3 text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697A11.025 11.025 0 0112 2.5c3.95 0 7.33 2.6 8.648 6.197a11.73 11.73 0 01-17.296 0A10.985 10.985 0 017.835 4.697z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900">Cek status</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Pantau proses layanan Anda dengan mudah menggunakan nomor tiket yang sudah diberikan.</p>
                    <a href="{{ route('guest.searchTicket') }}" class="mt-5 inline-flex text-sm font-semibold text-violet-600 hover:text-violet-700">Cari tiket →</a>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
