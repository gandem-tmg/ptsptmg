<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Daftar Persyaratan</h2>
    </x-slot>

    <div class="space-y-4 py-2">
        <form method="GET" action="{{ route('petugas.persyaratan.index') }}">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari persyaratan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
        </form>

        @forelse($persyaratans as $persyaratan)
        <a href="{{ route('petugas.persyaratan.show', $persyaratan) }}" class="soft-card block p-4 transition hover:border-emerald-300 hover:shadow-md sm:p-5">
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
                <span class="shrink-0 text-xs font-semibold text-emerald-600">Lihat detail &rarr;</span>
            </div>
        </a>
        @empty
        <div class="soft-card p-10 text-center text-sm text-slate-500">Belum ada persyaratan.</div>
        @endforelse

        <div class="pt-2">
            {{ $persyaratans->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
