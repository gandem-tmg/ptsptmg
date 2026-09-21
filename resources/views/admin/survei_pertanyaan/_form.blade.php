@php $p = $pertanyaan ?? null; @endphp

<div>
    <label class="mb-1 block text-sm font-medium text-slate-700">Teks Pertanyaan</label>
    <textarea name="teks_pertanyaan" rows="2" required
              class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">{{ old('teks_pertanyaan', $p->teks_pertanyaan ?? '') }}</textarea>
    @error('teks_pertanyaan') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
</div>

<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Tipe Jawaban</label>
        <select name="tipe" id="tipe-select" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
            @foreach(['skala_4' => 'Skala 1-4 (A-D, sesuai SKM resmi)', 'pilihan_ganda' => 'Pilihan Ganda Bebas', 'teks' => 'Teks Bebas / Komentar'] as $val => $label)
                <option value="{{ $val }}" @selected(old('tipe', $p->tipe ?? 'skala_4') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Jenis Survei</label>
        <select name="jenis_survei" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
            @foreach(['per_layanan' => 'Per-Layanan (setelah selesai)', 'umum' => 'Survei Umum (navbar)', 'keduanya' => 'Keduanya'] as $val => $label)
                <option value="{{ $val }}" @selected(old('jenis_survei', $p->jenis_survei ?? 'keduanya') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Urutan Tampil</label>
        <input type="number" name="urutan" min="0" value="{{ old('urutan', $p->urutan ?? 0) }}"
               class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
    </div>
</div>

<div class="mt-4" id="opsi-wrapper">
    <label class="mb-1 block text-sm font-medium text-slate-700">
        Opsi Jawaban (satu baris satu opsi)
    </label>
    <p class="mb-1 text-xs text-slate-400" id="opsi-hint">
        Untuk Skala 1-4: isi 4 baris label sesuai urutan A (paling rendah) sampai D (paling tinggi).
        Contoh: "Tidak Sesuai" / "Kurang Sesuai" / "Sesuai" / "Sangat Sesuai". Kosongkan untuk pakai label default.
    </p>
    <textarea name="opsi_jawaban_text" rows="4" placeholder="Tidak Sesuai&#10;Kurang Sesuai&#10;Sesuai&#10;Sangat Sesuai"
              class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">{{ old('opsi_jawaban_text', isset($p) && $p->opsi_jawaban ? implode("\n", $p->opsi_jawaban) : '') }}</textarea>
</div>

<div class="mt-4 flex items-center gap-2">
    <input type="checkbox" name="aktif" id="aktif" value="1" @checked(old('aktif', $p->aktif ?? true)) class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
    <label for="aktif" class="text-sm text-slate-700">Aktif (tampil di form survei)</label>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipeSelect = document.getElementById('tipe-select');
        const opsiWrapper = document.getElementById('opsi-wrapper');
        function toggleOpsi() {
            opsiWrapper.style.display = tipeSelect.value === 'teks' ? 'none' : 'block';
        }
        tipeSelect.addEventListener('change', toggleOpsi);
        toggleOpsi();
    });
</script>
