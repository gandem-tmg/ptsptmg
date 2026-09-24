<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Semua Permohonan</h2>
    </x-slot>

    <div class="space-y-4 py-2">
        @if(session('success'))
            <div class="flash-banner rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.permohonan.index') }}" class="filter-toolbar">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no tiket / nama pemohon / layanan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
            <x-permohonan-filter-bar :seksis="$seksis" :reset-route="route('admin.permohonan.index')" />
        </form>

        @if($permohonans->isEmpty())
            <div class="soft-card p-10 text-center text-sm text-slate-500">Belum ada permohonan.</div>
        @else
            <x-data-table>
                <thead>
                    <tr>
                        <th class="w-10">No</th>
                        <th>No Tiket</th>
                        <th>Pemohon &amp; Layanan</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permohonans as $permohonan)
                    <tr class="row-clickable" onclick="location.href='{{ route('admin.permohonan.show', $permohonan) }}'">
                        <td class="text-slate-400">{{ $permohonans->firstItem() + $loop->index }}</td>
                        <td class="whitespace-nowrap font-medium text-slate-900">{{ $permohonan->no_tiket }}</td>
                        <td class="max-w-xs">
                            <p class="font-medium text-slate-900">{{ $permohonan->user->name ?? $permohonan->nama }}</p>
                            <p class="mt-0.5 truncate text-xs text-slate-400">
                                {{ $permohonan->nama_layanan_label }}
                                @if($permohonan->is_manual)
                                    <span class="ml-1 rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-semibold text-amber-700">Manual</span>
                                @endif
                            </p>
                        </td>
                        <td>
                            <span class="whitespace-nowrap rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">
                                {{ $permohonan->lokasi_saat_ini }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap">{{ $permohonan->tanggal_pengajuan?->format('d M Y') }}</td>
                        <td><x-status-badge :status="$permohonan->status" class="whitespace-nowrap" /></td>
                        <td class="text-right" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.permohonan.show', $permohonan) }}" title="Lihat" class="icon-action icon-action-view">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </a>
                                <a href="{{ route('admin.permohonan.edit', $permohonan) }}" title="Edit" class="icon-action icon-action-edit">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </x-data-table>

            <div class="flex items-center justify-between pt-2 text-sm text-slate-500">
                <span>{{ $permohonans->firstItem() }}–{{ $permohonans->lastItem() }} dari {{ $permohonans->total() }}</span>
                {{ $permohonans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
