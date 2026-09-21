<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.layanan.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="kode_layanan" class="block text-sm font-medium text-gray-700">Kode Layanan</label>
                            <input type="text" name="kode_layanan" id="kode_layanan" value="{{ old('kode_layanan') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                            @error('kode_layanan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nama_layanan" class="block text-sm font-medium text-gray-700">Nama Layanan</label>
                            <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                            @error('nama_layanan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="seksi_id" class="block text-sm font-medium text-gray-700">Seksi Penanggung Jawab</label>
                            <select name="seksi_id" id="seksi_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                                <option value="">-- Pilih Seksi --</option>
                                @foreach($seksis as $seksi)
                                    <option value="{{ $seksi->id }}" {{ old('seksi_id') == $seksi->id ? 'selected' : '' }}>{{ $seksi->nama_seksi }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Permohonan layanan ini otomatis didisposisikan ke seksi ini oleh PTSP.</p>
                            @error('seksi_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tipe_pelaksanaan" class="block text-sm font-medium text-gray-700">Tipe Pelaksanaan</label>
                            <select name="tipe_pelaksanaan" id="tipe_pelaksanaan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="" {{ old('tipe_pelaksanaan') === null || old('tipe_pelaksanaan') === '' ? 'selected' : '' }}>-- Belum Ditentukan (badge tidak ditampilkan ke publik) --</option>
                                <option value="full_digital" {{ old('tipe_pelaksanaan') == 'full_digital' ? 'selected' : '' }}>Full Digital</option>
                                <option value="perlu_fisik" {{ old('tipe_pelaksanaan') == 'perlu_fisik' ? 'selected' : '' }}>Perlu Kehadiran/Dokumen Fisik</option>
                                <option value="sistem_eksternal" {{ old('tipe_pelaksanaan') == 'sistem_eksternal' ? 'selected' : '' }}>Dikerjakan via Sistem Nasional</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Ini hanya badge informasi untuk pemohon. Alur disposisi & tracking tetap sama untuk ketiganya. Kalau belum yakin, biarkan "Belum Ditentukan" — daripada salah tebak dan bikin pemohon bingung, badge-nya cukup disembunyikan dulu sampai dipastikan.</p>
                            @error('tipe_pelaksanaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-klasifikasi-fieldset />

                        <div class="mb-6 rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="perlu_dokumen_hasil" value="1" checked class="mt-1 rounded border-slate-300 text-emerald-600">
                                <span>
                                    <span class="block text-sm font-medium text-gray-700">Layanan ini menghasilkan dokumen/surat resmi</span>
                                    <span class="block text-xs text-slate-500 mt-0.5">Centang kalau hasil akhirnya berupa surat/dokumen yang perlu diunggah petugas seksi (misal: surat rekomendasi). Kosongkan untuk layanan seperti konsultasi/bimbingan yang selesai begitu pemohon dilayani langsung, tanpa dokumen keluaran.</span>
                                </span>
                            </label>
                        </div>

                        <div class="mb-6 rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-medium text-gray-700">Poin Standar Pelayanan (opsional)</p>
                            <p class="mt-0.5 text-xs text-slate-500">Ditampilkan sebagai poin dropdown di halaman publik layanan ini, di samping Persyaratan. Boleh dikosongkan dulu dan diisi belakangan.</p>

                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="sistem_mekanisme_prosedur" class="block text-sm font-medium text-gray-700">Sistem, Mekanisme, dan Prosedur</label>
                                    <textarea name="sistem_mekanisme_prosedur" id="sistem_mekanisme_prosedur" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('sistem_mekanisme_prosedur') }}</textarea>
                                    @error('sistem_mekanisme_prosedur')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="jangka_waktu_pelayanan" class="block text-sm font-medium text-gray-700">Jangka Waktu Pelayanan</label>
                                    <textarea name="jangka_waktu_pelayanan" id="jangka_waktu_pelayanan" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('jangka_waktu_pelayanan') }}</textarea>
                                    @error('jangka_waktu_pelayanan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="biaya_tarif" class="block text-sm font-medium text-gray-700">Biaya / Tarif</label>
                                    <textarea name="biaya_tarif" id="biaya_tarif" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('biaya_tarif') }}</textarea>
                                    @error('biaya_tarif')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="produk_pelayanan" class="block text-sm font-medium text-gray-700">Produk Pelayanan</label>
                                    <textarea name="produk_pelayanan" id="produk_pelayanan" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('produk_pelayanan') }}</textarea>
                                    @error('produk_pelayanan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Persyaratan</label>
                            <x-persyaratan-fieldset />
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.layanan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
