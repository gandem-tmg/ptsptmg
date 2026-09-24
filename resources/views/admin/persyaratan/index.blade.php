<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-slate-900">Daftar Persyaratan</h2>
            <a href="{{ route('admin.persyaratan.create') }}" class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 sm:px-4 sm:text-sm">
                + Tambah Persyaratan
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 py-2">
        @if(session('success'))
            <div class="flash-banner rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.persyaratan.index') }}" class="filter-toolbar">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari persyaratan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
        </form>

        <x-data-table :empty="$persyaratans->isEmpty()" empty-message="Belum ada persyaratan.">
            <thead>
                <tr>
                    <th class="w-10">No</th>
                    <th>Nama Persyaratan</th>
                    <th>Layanan</th>
                    <th>Sifat</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($persyaratans as $persyaratan)
                <tr class="row-clickable" onclick="location.href='{{ route('admin.persyaratan.edit', $persyaratan) }}'">
                    <td class="text-slate-400">{{ $persyaratans->firstItem() + $loop->index }}</td>
                    <td class="max-w-md">{{ $persyaratan->nama_persyaratan }}</td>
                    <td>{{ $persyaratan->layanan->nama_layanan ?? '-' }}</td>
                    <td>
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $persyaratan->wajib ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $persyaratan->wajib ? 'Wajib' : 'Opsional' }}
                        </span>
                    </td>
                    <td class="text-right" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.persyaratan.edit', $persyaratan) }}"
                               title="Edit"
                               class="icon-action icon-action-edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.persyaratan.destroy', $persyaratan) }}" onsubmit="return confirm('Hapus persyaratan ini?')">
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
            {{ $persyaratans->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
