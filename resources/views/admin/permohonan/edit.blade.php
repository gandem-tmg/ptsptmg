<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div><p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Manajemen permohonan</p><h2 class="mt-1 text-xl font-bold text-slate-900">Perbarui Status</h2></div>
            <a href="{{ route('admin.permohonan.show', $permohonan) }}" class="secondary-btn">Kembali</a>
        </div>
    </x-slot>

    <div class="workspace-page"><div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.permohonan.update', $permohonan) }}" class="soft-card p-5 sm:p-6">
            @csrf
            @method('PUT')
            <div class="mb-6 rounded-xl bg-emerald-50 p-4"><p class="text-sm text-emerald-800">Tiket <span class="font-mono font-bold">{{ $permohonan->no_tiket }}</span> · {{ $permohonan->layanan->nama_layanan }}</p></div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label for="status" class="field-label">Status permohonan</label><select name="status" id="status" class="form-input" required><option value="Diajukan" {{ strtolower($permohonan->status) == 'diajukan' ? 'selected' : '' }}>Diajukan</option><option value="Verifikasi" {{ strtolower($permohonan->status) == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option><option value="Proses" {{ strtolower($permohonan->status) == 'proses' ? 'selected' : '' }}>Proses</option><option value="Selesai" {{ strtolower($permohonan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option><option value="Ditolak" {{ strtolower($permohonan->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option></select>@error('status')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                <div><label for="no_tiket_admin" class="field-label">Nomor tiket admin</label><input type="text" name="no_tiket_admin" id="no_tiket_admin" value="{{ old('no_tiket_admin', $permohonan->no_tiket_admin) }}" class="form-input" placeholder="Opsional">@error('no_tiket_admin')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
            </div>
            <div class="mt-5"><label for="catatan_admin" class="field-label">Catatan untuk pemohon</label><textarea name="catatan_admin" id="catatan_admin" rows="5" class="form-input" placeholder="Sampaikan informasi atau tindak lanjut yang diperlukan">{{ old('catatan_admin', $permohonan->catatan_admin) }}</textarea>@error('catatan_admin')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"><a href="{{ route('admin.permohonan.show', $permohonan) }}" class="secondary-btn">Batal</a><button type="submit" class="primary-btn">Simpan Perubahan</button></div>
        </form>
    </div></div>
</x-app-layout>
