<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-slate-900">Permohonan Terbaru</h2>
            <div class="hidden shrink-0 items-center gap-2 sm:flex">
                <a href="{{ route('petugas.permohonan.daftar') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 sm:px-4 sm:text-sm">
                    Lihat Semua (Tabel)
                </a>
                {{-- Disembunyikan di mobile: sudah ada shortcut "Langsung" di bottom nav,
                     tombol ini bikin header padat di layar kecil. Menu Permohonan Langsung
                     sendiri tetap bisa diakses lewat bottom nav / sidebar seperti biasa. --}}
                <a href="{{ route('petugas.permohonan.offline.index') }}" class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 sm:px-4 sm:text-sm">
                    + Permohonan Langsung
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4 py-2">
        <form method="GET" action="{{ route('petugas.permohonan.index') }}" class="filter-toolbar">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no tiket / nama pemohon / layanan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
            <x-permohonan-filter-bar :seksis="$seksis" :reset-route="route('petugas.permohonan.index')" />
        </form>

        @if($permohonans->isEmpty())
            <div class="soft-card p-10 text-center text-sm text-slate-500">
                Belum ada permohonan.
            </div>
        @else
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($permohonans as $permohonan)
                    <x-permohonan-card :permohonan="$permohonan" route-prefix="petugas" :show-seksi="true">
                        <x-slot:actions>
                            @if(in_array($permohonan->status, ['diajukan', 'verifikasi_ptsp']))
                                <form method="POST" action="{{ route('petugas.permohonan.disposisikan', $permohonan) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                        Disposisikan Cepat
                                    </button>
                                </form>
                            @elseif($permohonan->status === 'selesai_seksi')
                                <form method="POST" action="{{ route('petugas.permohonan.verifikasiAkhir', $permohonan) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                        Verifikasi &amp; Selesaikan
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('petugas.permohonan.show', $permohonan) }}" class="ml-auto text-xs font-semibold text-slate-500 hover:text-slate-700">
                                Lihat Detail
                            </a>
                        </x-slot:actions>
                    </x-permohonan-card>
                @endforeach
            </div>

            {{-- Feed ini sengaja dibatasi ({{ $limit }} permohonan terbaru), tidak
                 dipaginasi — kalau data yang cocok filter lebih banyak dari itu,
                 arahkan ke "Daftar Permohonan" (tabel) yang memang dirancang
                 untuk menyisir semua data + paginasi penuh. --}}
            <div class="soft-card flex flex-col items-center justify-between gap-3 p-4 text-sm text-slate-500 sm:flex-row">
                <span>
                    Menampilkan {{ $permohonans->count() }} permohonan terbaru
                    @if($totalKeseluruhan > $permohonans->count())
                        dari total {{ $totalKeseluruhan }} yang cocok
                    @endif
                </span>
                <a href="{{ route('petugas.permohonan.daftar') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat semua di Daftar Permohonan (tabel) &rarr;
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
