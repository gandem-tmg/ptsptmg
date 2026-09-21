@props(['layanan' => null])

@php
    $kategoriList = config('klasifikasi_layanan.kategori');
    $personaList = config('klasifikasi_layanan.target_pengguna');
    $jenisList = config('klasifikasi_layanan.jenis_layanan');

    $oldKategori = old('kategori', $layanan->kategori ?? '');
    $oldSubkategori = old('subkategori', $layanan->subkategori ?? '');
    $oldTargetPengguna = old('target_pengguna', $layanan->target_pengguna ?? []);
    $oldTagPencarian = old('tag_pencarian', $layanan->tag_pencarian ?? '');
    $oldJenisLayanan = old('jenis_layanan', $layanan->jenis_layanan ?? '');
@endphp

<div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50/40 p-4">
    <p class="text-sm font-medium text-gray-700">Klasifikasi Kebutuhan (untuk halaman publik)</p>
    <p class="mt-0.5 text-xs text-slate-500">
        Ini menentukan tempat layanan muncul di halaman publik (grid kategori, hasil pencarian, dan wizard
        "Tidak tahu harus pilih layanan apa?"). Terpisah dari Seksi Penanggung Jawab di atas — Seksi tetap
        dipakai untuk disposisi internal, kolom di bawah ini murni untuk navigasi pengguna.
    </p>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <div>
            <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori Kebutuhan</label>
            <select name="kategori" id="kategori" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoriList as $slug => $def)
                    <option value="{{ $slug }}" {{ $oldKategori == $slug ? 'selected' : '' }}>{{ $def['label'] }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500">Kosongkan dulu kalau belum yakin — layanan tetap tersimpan, hanya belum tampil di grid publik.</p>
            @error('kategori')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="subkategori" class="block text-sm font-medium text-gray-700">Subkategori (opsional)</label>
            <select name="subkategori" id="subkategori" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">-- Tanpa Subkategori --</option>
                @foreach($kategoriList as $slug => $def)
                    @foreach(($def['subkategori'] ?? []) as $subSlug => $subLabel)
                        <option value="{{ $subSlug }}" {{ $oldSubkategori == $subSlug ? 'selected' : '' }}>{{ $def['label'] }} &rarr; {{ $subLabel }}</option>
                    @endforeach
                @endforeach
            </select>
            @error('subkategori')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="mt-4">
        <label for="jenis_layanan" class="block text-sm font-medium text-gray-700">Jenis Layanan (bentuk proses)</label>
        <select name="jenis_layanan" id="jenis_layanan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
            <option value="">-- Pilih Jenis --</option>
            @foreach($jenisList as $slug => $label)
                <option value="{{ $slug }}" {{ $oldJenisLayanan == $slug ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-500">Tampil sebagai badge kecil di kartu layanan. Bukan kategori navigasi.</p>
        @error('jenis_layanan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700">Untuk Siapa Layanan Ini? (bisa pilih lebih dari satu)</label>
        <p class="mt-0.5 text-xs text-slate-500">Dipakai fitur wizard "Tidak tahu harus pilih layanan apa?" — pengguna memilih dirinya siapa, sistem menyaring dari tag ini.</p>
        <div class="mt-2 grid gap-2 sm:grid-cols-2">
            @foreach($personaList as $slug => $def)
            <label class="flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm">
                <input type="checkbox" name="target_pengguna[]" value="{{ $slug }}"
                       {{ in_array($slug, (array) $oldTargetPengguna) ? 'checked' : '' }}
                       class="rounded border-slate-300 text-emerald-600">
                <span>{{ $def['label'] }}</span>
            </label>
            @endforeach
        </div>
        @error('target_pengguna')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="mt-4">
        <label for="tag_pencarian" class="block text-sm font-medium text-gray-700">Kata Kunci Pencarian (dipisah koma)</label>
        <textarea name="tag_pencarian" id="tag_pencarian" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="mis. ijazah hilang, ijazah rusak, surat keterangan pengganti ijazah">{{ $oldTagPencarian }}</textarea>
        <p class="mt-1 text-xs text-slate-500">Isi istilah awam yang mungkin diketik orang, bukan cuma nama resmi SP — biar ketemu lewat kotak pencarian.</p>
        @error('tag_pencarian')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
