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

        <form method="GET" action="{{ route('admin.persyaratan.index') }}">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari persyaratan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
        </form>

        @if($persyaratans->isEmpty())
            <div class="soft-card p-10 text-center text-sm text-slate-500">Belum ada persyaratan.</div>
        @else
            <div class="space-y-3">
                @foreach($persyaratans as $persyaratan)
                <div class="soft-card p-4 sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium leading-relaxed text-slate-900">{{ $persyaratan->nama_persyaratan }}</p>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-600">{{ $persyaratan->layanan->nama_layanan ?? '-' }}</span>
                                <span class="rounded-full px-2.5 py-1 font-medium {{ $persyaratan->wajib ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $persyaratan->wajib ? 'Wajib' : 'Opsional' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-1">
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
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pt-2">
                {{ $persyaratans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
