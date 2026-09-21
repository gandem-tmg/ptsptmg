<x-public-layout>
    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">

        <!-- Hero -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-start gap-4 border-l-4 border-emerald-700 bg-emerald-50/40 px-5 py-6 sm:px-7 sm:py-7">
                <span class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-emerald-700 text-white sm:flex">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286z" /></svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Survei Kepuasan Masyarakat</p>
                    <h1 class="mt-1 text-xl font-bold leading-snug text-slate-900 sm:text-2xl">Bagaimana pengalaman Anda dengan layanan kami?</h1>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Masukan Anda membantu kami meningkatkan kualitas pelayanan publik. Survei ini dapat diisi
                        kapan saja, baik oleh masyarakat yang sudah maupun belum pernah menggunakan layanan kami.
                    </p>
                    <p class="mt-3 text-xs text-slate-400">Mengacu pada Peraturan Menteri PANRB Nomor 14 Tahun 2017 tentang Pedoman Survei Kepuasan Masyarakat.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="flash-banner mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        @if($pertanyaans->isEmpty())
            <div class="soft-card mt-5 p-5 text-center text-sm text-slate-500">
                Survei belum tersedia saat ini. Silakan coba lagi nanti.
            </div>
        @else
            <form method="POST" action="{{ route('survei.umum.store') }}" class="mt-5 space-y-3">
                @csrf

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <label class="mb-1.5 block text-sm font-semibold text-slate-800">Nama Anda</label>
                    <input type="text" name="nama_pengisi" value="{{ old('nama_pengisi') }}" required maxlength="150"
                           class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200"
                           placeholder="Nama lengkap">
                    @error('nama_pengisi') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror

                    <div class="mt-4 border-t border-slate-100 pt-4">
                        <p class="text-xs font-medium text-slate-500">Data diri singkat berikut ini opsional, membantu kami membaca hasil survei lebih akurat per kelompok masyarakat.</p>
                        <div class="mt-3 grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-600">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                                    <option value="">Pilih</option>
                                    <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                                    <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-600">Usia</label>
                                <select name="usia_rentang" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                                    <option value="">Pilih</option>
                                    @foreach(\App\Models\SurveiRespon::OPSI_USIA as $opsi)
                                        <option value="{{ $opsi }}" @selected(old('usia_rentang') === $opsi)>{{ $opsi }}</option>
                                    @endforeach
                                </select>
                                @error('usia_rentang') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-600">Pendidikan Terakhir</label>
                                <select name="pendidikan_terakhir" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                                    <option value="">Pilih</option>
                                    @foreach(\App\Models\SurveiRespon::OPSI_PENDIDIKAN as $opsi)
                                        <option value="{{ $opsi }}" @selected(old('pendidikan_terakhir') === $opsi)>{{ $opsi }}</option>
                                    @endforeach
                                </select>
                                @error('pendidikan_terakhir') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-600">Pekerjaan Utama</label>
                                <select name="pekerjaan_utama" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                                    <option value="">Pilih</option>
                                    @foreach(\App\Models\SurveiRespon::OPSI_PEKERJAAN as $opsi)
                                        <option value="{{ $opsi }}" @selected(old('pekerjaan_utama') === $opsi)>{{ $opsi }}</option>
                                    @endforeach
                                </select>
                                @error('pekerjaan_utama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                @include('survei._pertanyaan_fields', ['pertanyaans' => $pertanyaans])

                <button type="submit" class="primary-btn w-full py-3 text-sm">
                    Kirim Survei
                </button>
            </form>
        @endif
    </div>
</x-public-layout>
