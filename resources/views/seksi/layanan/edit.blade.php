<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Edit Layanan</h2>
    </x-slot>

    <div class="py-2">
        <div class="soft-card p-6">
            <div class="mb-6 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                <p><strong class="text-slate-800">{{ $layanan->nama_layanan }}</strong> ({{ $layanan->kode_layanan }})</p>
                <p class="mt-1 text-xs text-slate-500">
                    Nama layanan, kode layanan, seksi penanggung jawab, dan klasifikasi kebutuhan publik
                    diatur oleh admin. Hubungi admin bila hal-hal tersebut perlu diubah.
                </p>
            </div>

            <form method="POST" action="{{ route('seksi.layanan.update', $layanan) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                    @error('deskripsi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="tipe_pelaksanaan" class="block text-sm font-medium text-gray-700">Tipe Pelaksanaan</label>
                    <select name="tipe_pelaksanaan" id="tipe_pelaksanaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="" {{ !old('tipe_pelaksanaan', $layanan->tipe_pelaksanaan) ? 'selected' : '' }}>-- Belum Ditentukan (badge tidak ditampilkan ke publik) --</option>
                        <option value="full_digital" {{ old('tipe_pelaksanaan', $layanan->tipe_pelaksanaan) == 'full_digital' ? 'selected' : '' }}>Full Digital</option>
                        <option value="perlu_fisik" {{ old('tipe_pelaksanaan', $layanan->tipe_pelaksanaan) == 'perlu_fisik' ? 'selected' : '' }}>Perlu Kehadiran/Dokumen Fisik</option>
                        <option value="sistem_eksternal" {{ old('tipe_pelaksanaan', $layanan->tipe_pelaksanaan) == 'sistem_eksternal' ? 'selected' : '' }}>Dikerjakan via Sistem Nasional</option>
                    </select>
                    @error('tipe_pelaksanaan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="mb-6 rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="perlu_dokumen_hasil" value="1" {{ old('perlu_dokumen_hasil', $layanan->perlu_dokumen_hasil) ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-emerald-600">
                        <span>
                            <span class="block text-sm font-medium text-gray-700">Layanan ini menghasilkan dokumen/surat resmi</span>
                            <span class="block text-xs text-slate-500 mt-0.5">Menentukan tampilan form penyelesaian saat Anda menuntaskan permohonan layanan ini.</span>
                        </span>
                    </label>
                </div>

                <div class="mb-6 rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-medium text-gray-700">Poin Standar Pelayanan (opsional)</p>
                    <p class="mt-0.5 text-xs text-slate-500">Ditampilkan sebagai poin dropdown di halaman publik layanan ini, di samping Persyaratan.</p>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="sistem_mekanisme_prosedur" class="block text-sm font-medium text-gray-700">Sistem, Mekanisme, dan Prosedur</label>
                            <textarea name="sistem_mekanisme_prosedur" id="sistem_mekanisme_prosedur" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('sistem_mekanisme_prosedur', $layanan->sistem_mekanisme_prosedur) }}</textarea>
                        </div>
                        <div>
                            <label for="jangka_waktu_pelayanan" class="block text-sm font-medium text-gray-700">Jangka Waktu Pelayanan</label>
                            <textarea name="jangka_waktu_pelayanan" id="jangka_waktu_pelayanan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('jangka_waktu_pelayanan', $layanan->jangka_waktu_pelayanan) }}</textarea>
                        </div>
                        <div>
                            <label for="biaya_tarif" class="block text-sm font-medium text-gray-700">Biaya / Tarif</label>
                            <textarea name="biaya_tarif" id="biaya_tarif" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('biaya_tarif', $layanan->biaya_tarif) }}</textarea>
                        </div>
                        <div>
                            <label for="produk_pelayanan" class="block text-sm font-medium text-gray-700">Produk Pelayanan</label>
                            <textarea name="produk_pelayanan" id="produk_pelayanan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('produk_pelayanan', $layanan->produk_pelayanan) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Persyaratan</label>
                    <x-persyaratan-fieldset :persyaratan-list="$layanan->persyaratan" />
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('seksi.layanan.show', $layanan) }}" class="rounded bg-gray-500 px-4 py-2 font-bold text-white hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 font-bold text-white hover:bg-emerald-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
