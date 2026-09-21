<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Buat Tiket — {{ $layanan->nama_layanan }}</h2>
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

        <form method="POST" action="{{ route('petugas.permohonan.offline.store') }}">
            @csrf
            <input type="hidden" name="layanan_id" value="{{ $layanan->id }}">

            <!-- Info layanan -->
            <div class="soft-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ $layanan->seksi->nama_seksi ?? '-' }}</p>
                <h3 class="mt-1 font-semibold text-slate-900">{{ $layanan->nama_layanan }}</h3>
                <a href="{{ route('petugas.permohonan.offline.index') }}" class="mt-2 inline-block text-xs font-semibold text-slate-500 hover:text-slate-700">
                    &larr; Ganti layanan
                </a>
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

            <!-- Checklist persyaratan -->
            <div class="soft-card mt-6 p-6">
                <h3 class="mb-1 text-sm font-semibold text-slate-900">Kelengkapan Persyaratan</h3>
                <p class="mb-4 text-xs text-slate-500">Centang setelah dicek fisik oleh petugas. Yang <span class="font-semibold text-rose-600">wajib</span> harus lengkap semua sebelum tiket bisa diterbitkan.</p>

                @if($layanan->persyaratan->isEmpty())
                    <p class="text-sm text-slate-400">Layanan ini tidak memerlukan persyaratan khusus.</p>
                @else
                    <div class="space-y-2">
                        @foreach($layanan->persyaratan as $p)
                        <label class="flex items-start gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm">
                            <input type="checkbox" name="persyaratan_checked[]" value="{{ $p->id }}"
                                   {{ in_array($p->id, old('persyaratan_checked', [])) ? 'checked' : '' }}
                                   class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span>
                                {{ $p->nama_persyaratan }}
                                @if($p->wajib)
                                    <span class="required-mark">*</span>
                                @endif
                            </span>
                        </label>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('petugas.permohonan.offline.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Batal</a>
                <button type="submit" class="primary-btn px-6">Terbitkan Tiket</button>
            </div>
        </form>
    </div>
</x-app-layout>
