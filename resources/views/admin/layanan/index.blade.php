<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-slate-900">Daftar Layanan</h2>
            <a href="{{ route('admin.layanan.create') }}" class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 sm:px-4 sm:text-sm">
                + Tambah Layanan
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 py-2">
        @if(session('success'))
            <div class="flash-banner rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        @if($belumDikategorikanCount > 0)
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                PERINGATAN: Ada <strong>{{ $belumDikategorikanCount }}</strong> layanan yang belum diisi Kategori Kebutuhan —
                layanan tersebut tidak akan muncul di halaman publik sampai dilengkapi lewat menu Edit.
            </div>
        @endif

        <form method="GET" action="{{ route('admin.layanan.index') }}" class="filter-toolbar">
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
                    <th>Tipe Pelaksanaan</th>
                    <th>Persyaratan</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($layanans as $layanan)
                <tr class="row-clickable" onclick="location.href='{{ route('admin.layanan.show', $layanan) }}'">
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
                    <td>
                        @if($layanan->tipe_pelaksanaan)
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide
                            @if($layanan->tipe_pelaksanaan == 'full_digital') bg-green-100 text-green-700
                            @elseif($layanan->tipe_pelaksanaan == 'perlu_fisik') bg-amber-100 text-amber-700
                            @else bg-sky-100 text-sky-700 @endif">
                            {{ str_replace('_', ' ', $layanan->tipe_pelaksanaan) }}
                        </span>
                        @else
                        <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td>{{ $layanan->persyaratan->count() }} item</td>
                    <td class="text-right" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.layanan.show', $layanan) }}" title="Lihat" class="icon-action icon-action-view">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </a>
                            <a href="{{ route('admin.layanan.edit', $layanan) }}" title="Edit" class="icon-action icon-action-edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.layanan.destroy', $layanan) }}" onsubmit="return confirm('Hapus layanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" class="icon-action icon-action-danger">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
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
