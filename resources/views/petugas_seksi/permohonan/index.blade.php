<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-slate-900">
                Permohonan Terbaru
                <span class="block text-sm font-normal text-slate-500 sm:inline sm:ml-2">{{ auth()->user()->seksi->nama_seksi ?? '-' }}</span>
            </h2>
            <a href="{{ route('seksi.permohonan.daftar') }}" class="hidden shrink-0 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 sm:inline-flex sm:px-4 sm:text-sm">
                Lihat Semua (Tabel)
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 py-2">
        @if(session('success'))
            <div class="flash-banner rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('seksi.permohonan.index') }}" class="filter-toolbar">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no tiket / nama pemohon..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
            <x-permohonan-filter-bar :reset-route="route('seksi.permohonan.index')" />
        </form>

        @if($permohonans->isEmpty())
            <div class="soft-card p-10 text-center text-sm text-slate-500">
                Belum ada permohonan yang didisposisikan ke seksi ini.
            </div>
        @else
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($permohonans as $permohonan)
                    <x-permohonan-card :permohonan="$permohonan" route-prefix="seksi" :show-seksi="false">
                        <x-slot:actions>
                            @if($permohonan->status === 'didisposisikan')
                                <form method="POST" action="{{ route('seksi.permohonan.terima', $permohonan) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                        Terima Cepat
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('seksi.permohonan.show', $permohonan) }}" class="ml-auto text-xs font-semibold text-slate-500 hover:text-slate-700">
                                {{ in_array($permohonan->status, ['didisposisikan', 'diproses_seksi']) ? 'Tindak Lanjuti' : 'Lihat Detail' }}
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
                <a href="{{ route('seksi.permohonan.daftar') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat semua di Daftar Permohonan (tabel) &rarr;
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
