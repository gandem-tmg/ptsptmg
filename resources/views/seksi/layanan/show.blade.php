<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900">{{ $layanan->nama_layanan }}</h2>
            <a href="{{ route('seksi.layanan.edit', $layanan) }}" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white hover:bg-emerald-700">
                Edit Layanan
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 py-2">
        <div class="soft-card space-y-3 p-5">
            <div>
                <p class="text-xs text-slate-400">Kode Layanan</p>
                <p class="text-sm font-medium text-slate-800">{{ $layanan->kode_layanan }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Deskripsi</p>
                <p class="text-sm text-slate-800">{{ $layanan->deskripsi ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Tipe Pelaksanaan</p>
                <p class="text-sm text-slate-800">
                    @switch($layanan->tipe_pelaksanaan)
                        @case('full_digital') Full Digital @break
                        @case('perlu_fisik') Perlu Kehadiran/Dokumen Fisik @break
                        @case('sistem_eksternal') Dikerjakan via Sistem Nasional @break
                        @default Belum Ditentukan
                    @endswitch
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Menghasilkan Dokumen/Surat Resmi</p>
                <p class="text-sm text-slate-800">{{ $layanan->perlu_dokumen_hasil ? 'Ya' : 'Tidak' }}</p>
            </div>
        </div>

        <div class="soft-card space-y-3 p-5">
            <p class="text-sm font-medium text-slate-700">Poin Standar Pelayanan</p>
            <div>
                <p class="text-xs text-slate-400">Sistem, Mekanisme, dan Prosedur</p>
                <p class="text-sm text-slate-800">{{ $layanan->sistem_mekanisme_prosedur ?: 'Data belum tersedia' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Jangka Waktu Pelayanan</p>
                <p class="text-sm text-slate-800">{{ $layanan->jangka_waktu_pelayanan ?: 'Data belum tersedia' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Biaya/Tarif</p>
                <p class="text-sm text-slate-800">{{ $layanan->biaya_tarif ?: 'Data belum tersedia' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Produk Pelayanan</p>
                <p class="text-sm text-slate-800">{{ $layanan->produk_pelayanan ?: 'Data belum tersedia' }}</p>
            </div>
        </div>

        <div class="soft-card p-5">
            <p class="mb-3 text-sm font-medium text-slate-700">Persyaratan</p>
            @if($layanan->persyaratan->count() > 0)
                <ul class="space-y-1.5">
                    @foreach($layanan->persyaratan as $p)
                    <li class="flex items-center gap-2 text-sm text-slate-800">
                        <span>{{ $p->nama_persyaratan }}</span>
                        @if($p->wajib)
                            <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Wajib</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-slate-500">Belum ada persyaratan.</p>
            @endif
        </div>

        <div class="soft-card p-5">
            <p class="mb-3 text-sm font-medium text-slate-700">Riwayat Perubahan</p>
            @if($layanan->perubahanLog->count() > 0)
                <ul class="space-y-3">
                    @foreach($layanan->perubahanLog as $log)
                    <li class="border-l-2 border-emerald-200 pl-3 text-sm">
                        <p class="text-slate-800">{{ $log->ringkasan }}</p>
                        <p class="text-xs text-slate-400">
                            {{ $log->user->name ?? 'Pengguna terhapus' }} &middot; {{ $log->created_at->format('d M Y H:i') }}
                        </p>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-slate-500">Belum ada perubahan yang tercatat.</p>
            @endif
        </div>

        <a href="{{ route('seksi.layanan.index') }}" class="inline-block text-sm font-medium text-slate-500 hover:text-slate-700">
            &larr; Kembali ke Layanan Seksi Saya
        </a>
    </div>
</x-app-layout>
