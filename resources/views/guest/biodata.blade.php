<x-guest-layout>
    <div class="w-full">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">Langkah 1</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Isi Biodata</h2>
            </div>
            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">1 / 2</span>
        </div>

        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
            <p class="text-sm font-medium text-emerald-800">Pastikan data yang Anda masukkan valid agar proses verifikasi berjalan cepat.</p>
        </div>

        <form method="POST" action="{{ route('guest.permohonan.storeBiodata') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="nama" class="field-label">Nama Lengkap</label>
                <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required class="form-input" placeholder="Masukkan nama lengkap">
                @error('nama')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="alamat" class="field-label">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" required class="form-input" placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="nik" class="field-label">NIK</label>
                    <input id="nik" type="text" name="nik" value="{{ old('nik') }}" required class="form-input" placeholder="16 digit NIK">
                    @error('nik')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_hp" class="field-label">No. HP / WhatsApp</label>
                    <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required class="form-input" placeholder="08xxxxxxxxxx">
                    @error('no_hp')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="ktp" class="field-label">Upload KTP</label>
                <input id="ktp" type="file" name="ktp" accept="image/*,.pdf" required class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm transition duration-200 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                @error('ktp')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-slate-500">Format: JPG, PNG, atau PDF. Maksimal 2MB.</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="primary-btn w-full">
                    Lanjutkan ke Permohonan
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
