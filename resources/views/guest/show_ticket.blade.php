<x-guest-layout>
    <div class="w-full">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">Status</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Detail Permohonan</h2>
            </div>
            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $permohonan->no_tiket }}</span>
        </div>

        <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-sm text-slate-500">Nomor Tiket</p>
            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $permohonan->no_tiket }}</p>
        </div>

        <div class="space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <h3 class="mb-3 text-base font-semibold text-slate-900">Informasi Pemohon</h3>
                <dl class="space-y-2 text-sm text-slate-600">
                    <div class="flex justify-between gap-3"><dt class="font-medium text-slate-500">Nama</dt><dd class="text-right text-slate-800">{{ $permohonan->nama ?: $permohonan->user->name }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="font-medium text-slate-500">Alamat</dt><dd class="text-right text-slate-800">{{ $permohonan->alamat ?: 'N/A' }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="font-medium text-slate-500">NIK</dt><dd class="text-right text-slate-800">{{ $permohonan->nik ?: 'N/A' }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="font-medium text-slate-500">No. HP</dt><dd class="text-right text-slate-800">{{ $permohonan->no_hp ?: $permohonan->user->phone ?? 'N/A' }}</dd></div>
                </dl>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <h3 class="mb-3 text-base font-semibold text-slate-900">Informasi Permohonan</h3>
                <dl class="space-y-2 text-sm text-slate-600">
                    <div class="flex justify-between gap-3"><dt class="font-medium text-slate-500">Layanan</dt><dd class="text-right text-slate-800">{{ $permohonan->layanan->nama_layanan }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="font-medium text-slate-500">Tanggal</dt><dd class="text-right text-slate-800">{{ $permohonan->tanggal_pengajuan->format('d/m/Y') }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="font-medium text-slate-500">Status</dt><dd class="text-right">
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                            @if($permohonan->status == 'diajukan') bg-yellow-100 text-yellow-800
                            @elseif($permohonan->status == 'verifikasi') bg-blue-100 text-blue-800
                            @elseif($permohonan->status == 'proses') bg-purple-100 text-purple-800
                            @elseif($permohonan->status == 'selesai') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($permohonan->status) }}
                        </span>
                    </dd></div>
                    @if($permohonan->no_tiket_admin)
                        <div class="flex justify-between gap-3"><dt class="font-medium text-slate-500">No. Tiket Admin</dt><dd class="text-right text-slate-800">{{ $permohonan->no_tiket_admin }}</dd></div>
                    @endif
                </dl>
            </div>

            @if($permohonan->deskripsi)
                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <h3 class="mb-2 text-base font-semibold text-slate-900">Deskripsi Permohonan</h3>
                    <p class="text-sm leading-6 text-slate-600">{{ $permohonan->deskripsi }}</p>
                </div>
            @endif

            @if($permohonan->catatan_admin)
                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <h3 class="mb-2 text-base font-semibold text-slate-900">Catatan Admin</h3>
                    <p class="text-sm leading-6 text-slate-600">{{ $permohonan->catatan_admin }}</p>
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <h3 class="mb-3 text-base font-semibold text-slate-900">Lampiran Persyaratan</h3>
                @if($permohonan->lampiranPermohonan->count() > 0)
                    <ul class="space-y-2 text-sm text-slate-600">
                        @foreach($permohonan->lampiranPermohonan as $lampiran)
                            <li class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-3 py-2">
                                <span>{{ $lampiran->persyaratan->nama_persyaratan }}</span>
                                <a href="{{ Storage::url($lampiran->file_path) }}" target="_blank" class="font-medium text-emerald-700 hover:text-emerald-800">Lihat file</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-slate-500">Tidak ada lampiran.</p>
                @endif
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('guest.permohonan.pdf', $permohonan) }}" class="primary-btn w-full sm:w-auto">
                    Download PDF
                </a>
                <a href="{{ route('guest.searchTicket') }}" class="secondary-btn w-full sm:w-auto">
                    Cari Tiket Lain
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
