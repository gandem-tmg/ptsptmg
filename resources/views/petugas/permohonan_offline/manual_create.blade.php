<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Buat Tiket — Layanan di Luar Katalog</h2>
    </x-slot>

    <div class="mx-auto max-w-2xl space-y-6 py-2">

        @if($errors->any())
            <div class="flash-banner rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="list-inside list-disc space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Gunakan form ini hanya kalau permohonan pemohon memang <strong>tidak ada di daftar layanan</strong>
            seksi ini, tapi masih menjadi tugas &amp; fungsi Kemenag. Nama dan deskripsi layanan diisi manual.
        </div>

        <form method="POST" action="{{ route('petugas.permohonan.offline.manual.store') }}">
            @csrf
            <input type="hidden" name="seksi_id" value="{{ $seksi->id }}">

            <!-- Info seksi -->
            <div class="soft-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Unit Kerja / Seksi</p>
                <h3 class="mt-1 font-semibold text-slate-900">{{ $seksi->nama_seksi }}</h3>
                <a href="{{ route('petugas.permohonan.offline.index') }}" class="mt-2 inline-block text-xs font-semibold text-slate-500 hover:text-slate-700">
                    &larr; Ganti seksi
                </a>
            </div>

            <!-- Detail layanan manual -->
            <div class="soft-card mt-6 p-6">
                <h3 class="mb-4 text-sm font-semibold text-slate-900">Detail Layanan</h3>

                <label for="nama_layanan_manual" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Layanan</label>
                <input type="text" id="nama_layanan_manual" name="nama_layanan_manual" value="{{ old('nama_layanan_manual') }}" required
                       placeholder="Contoh: Legalisir Surat Keterangan Wakaf"
                       class="block w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">

                <label for="deskripsi_layanan_manual" class="mb-1.5 mt-4 block text-sm font-medium text-slate-700">Deskripsi Layanan <span class="font-normal text-slate-400">(opsional)</span></label>
                <textarea id="deskripsi_layanan_manual" name="deskripsi_layanan_manual" rows="3"
                          placeholder="Jelaskan singkat permohonan/kebutuhan pemohon"
                          class="block w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">{{ old('deskripsi_layanan_manual') }}</textarea>
            </div>

            <!-- Identitas pemohon -->
            <div class="soft-card mt-6 p-6">
                <h3 class="mb-4 text-sm font-semibold text-slate-900">Identitas Pemohon</h3>

                <label for="nama" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Pemohon / Instansi</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                       class="block w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">

                <label for="alamat" class="mb-1.5 mt-4 block text-sm font-medium text-slate-700">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2" required
                          class="block w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">{{ old('alamat') }}</textarea>

                <label for="no_hp" class="mb-1.5 mt-4 block text-sm font-medium text-slate-700">No. HP <span class="font-normal text-slate-400">(opsional)</span></label>
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                       class="block w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
            </div>

            <!-- Kelengkapan persyaratan -->
            <div class="soft-card mt-6 p-6">
                <h3 class="mb-1 text-sm font-semibold text-slate-900">Kelengkapan Persyaratan</h3>
                <p class="mb-4 text-xs text-slate-500">Karena layanan ini tidak punya daftar persyaratan resmi di sistem, cukup nyatakan status kelengkapan berkas secara keseluruhan berdasarkan pengecekan fisik di loket.</p>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <label class="flex items-center gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm cursor-pointer">
                        <input type="radio" name="kelengkapan_manual" value="lengkap" required
                               {{ old('kelengkapan_manual') === 'lengkap' ? 'checked' : '' }}
                               class="text-emerald-600 focus:ring-emerald-500">
                        <span class="font-medium text-slate-800">Lengkap</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm cursor-pointer">
                        <input type="radio" name="kelengkapan_manual" value="belum_lengkap"
                               {{ old('kelengkapan_manual') === 'belum_lengkap' ? 'checked' : '' }}
                               class="text-emerald-600 focus:ring-emerald-500">
                        <span class="font-medium text-slate-800">Belum Lengkap</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('petugas.permohonan.offline.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Batal</a>
                <button type="submit" class="primary-btn px-6">Terbitkan Tiket</button>
            </div>
        </form>
    </div>
</x-app-layout>
