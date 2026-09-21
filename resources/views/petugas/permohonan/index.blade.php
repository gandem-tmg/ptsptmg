<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-slate-900">Daftar Permohonan</h2>
            {{-- Disembunyikan di mobile: sudah ada shortcut "Offline" di bottom nav,
                 tombol ini bikin header padat di layar kecil. Menu Permohonan Offline
                 sendiri tetap bisa diakses lewat bottom nav / sidebar seperti biasa. --}}
            <a href="{{ route('petugas.permohonan.offline.index') }}" class="hidden shrink-0 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 sm:inline-flex sm:px-4 sm:text-sm">
                + Permohonan Offline
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 py-2">
        <form method="GET" action="{{ route('petugas.permohonan.index') }}">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no tiket / nama pemohon / layanan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
            <x-permohonan-filter-bar :seksis="$seksis" :reset-route="route('petugas.permohonan.index')" />
        </form>

        @if($permohonans->isEmpty())
            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
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

            <div class="flex items-center justify-between pt-2 text-sm text-slate-500">
                <span>{{ $permohonans->firstItem() }}–{{ $permohonans->lastItem() }} dari {{ $permohonans->total() }}</span>
                {{ $permohonans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
