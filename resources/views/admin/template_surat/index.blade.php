<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Template Surat') }}</h2>
            <a href="{{ route('admin.template-surat.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">+ Template Baru</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="flash-banner mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-[13px] text-emerald-800">{{ session('success') }}</div>
                    @endif
                    <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Template</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($templates as $template)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $template->judul_template }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $template->layanan->nama_layanan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-0.5 rounded-full text-xs {{ $template->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $template->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center gap-1">
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
                            @empty
                            <tr><td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center">Belum ada template surat</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                    <div class="mt-4">{{ $templates->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
