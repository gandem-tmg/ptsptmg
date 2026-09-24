<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-slate-900">Template Surat</h2>
            <a href="{{ route('admin.template-surat.create') }}" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">
                + Template Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 py-2">
        @if(session('success'))
            <div class="flash-banner rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <x-data-table :empty="$templates->isEmpty()" empty-message="Belum ada template surat.">
            <thead>
                <tr>
                    <th class="w-10">No</th>
                    <th>Judul Template</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                <tr class="row-clickable" onclick="location.href='{{ route('admin.template-surat.edit', $template) }}'">
                    <td class="text-slate-400">{{ $templates->firstItem() + $loop->index }}</td>
                    <td class="font-medium text-slate-900">{{ $template->judul_template }}</td>
                    <td>{{ $template->layanan->nama_layanan ?? '-' }}</td>
                    <td>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $template->aktif ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $template->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="text-right" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.template-surat.edit', $template) }}" title="Edit" class="icon-action icon-action-edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form action="{{ route('admin.template-surat.destroy', $template) }}" method="POST" onsubmit="return confirm('Hapus template ini?')">
                                @csrf @method('DELETE')
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

        <div class="pt-2">{{ $templates->links() }}</div>
    </div>
</x-app-layout>
