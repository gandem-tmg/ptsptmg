<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Daftar Layanan</h2>
    </x-slot>

    <div class="space-y-4 py-2">
        @if($belumDikategorikanCount > 0)
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                PERINGATAN: Ada <strong>{{ $belumDikategorikanCount }}</strong> layanan yang belum dikategorikan, belum muncul di halaman publik.
            </div>
        @endif

        <form method="GET" action="{{ route('petugas.layanan.index') }}">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
        </form>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($layanans as $layanan)
            <a href="{{ route('petugas.layanan.show', $layanan) }}" class="soft-card block p-5 transition hover:border-emerald-300 hover:shadow-md">
                <div class="mb-1 flex items-start justify-between gap-2">
                    <h3 class="font-semibold text-slate-900">{{ $layanan->nama_layanan }}</h3>
                </div>
                @if(!$layanan->kategori)
                <span class="mb-1 inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-semibold text-red-700">
                    BELUM DIKATEGORIKAN
                </span>
                @endif
                <p class="text-xs text-slate-400">{{ $layanan->kode_layanan }} &middot; {{ $layanan->seksi->nama_seksi ?? '-' }}</p>
                <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $layanan->deskripsi ?: 'Belum ada deskripsi.' }}</p>
            </a>
            @empty
            <div class="soft-card col-span-full p-10 text-center text-sm text-slate-500">Belum ada layanan.</div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $layanans->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
