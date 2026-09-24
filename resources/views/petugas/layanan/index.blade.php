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

        <form method="GET" action="{{ route('petugas.layanan.index') }}" class="filter-toolbar">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
        </form>

        <x-data-table :empty="$layanans->isEmpty()" empty-message="Belum ada layanan.">
            <thead>
                <tr>
                    <th class="w-10">No</th>
                    <th>Nama Layanan</th>
                    <th>Seksi</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($layanans as $layanan)
                <tr class="row-clickable" onclick="location.href='{{ route('petugas.layanan.show', $layanan) }}'">
                    <td class="text-slate-400">{{ $layanans->firstItem() + $loop->index }}</td>
                    <td>
                        <p class="font-medium text-slate-900">{{ $layanan->nama_layanan }}</p>
                        <p class="mt-0.5 text-xs text-slate-400">{{ $layanan->kode_layanan }}</p>
                        @if(!$layanan->kategori)
                        <span class="mt-1 inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-semibold text-red-700">
                            BELUM DIKATEGORIKAN
                        </span>
                        @endif
                    </td>
                    <td>{{ $layanan->seksi->nama_seksi ?? '-' }}</td>
                    <td class="text-right" onclick="event.stopPropagation()">
                        <a href="{{ route('petugas.layanan.show', $layanan) }}" title="Lihat" class="icon-action icon-action-view">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </x-data-table>

        <div class="pt-2">
            {{ $layanans->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
