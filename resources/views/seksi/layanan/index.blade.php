<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Layanan Seksi Saya</h2>
    </x-slot>

    <div class="space-y-4 py-2">
        <p class="text-sm text-slate-500">
            Layanan berikut ditugaskan ke seksi Anda. Anda dapat memperbarui deskripsi, persyaratan, dan
            standar pelayanannya — perubahan ini langsung tayang di halaman publik dan tercatat pada
            riwayat perubahan tiap layanan.
        </p>

        <form method="GET" action="{{ route('seksi.layanan.index') }}">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
        </form>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($layanans as $layanan)
            <a href="{{ route('seksi.layanan.show', $layanan) }}" class="soft-card block p-5 transition hover:border-emerald-300 hover:shadow-md">
                <h3 class="font-semibold text-slate-900">{{ $layanan->nama_layanan }}</h3>
                <p class="text-xs text-slate-400">{{ $layanan->kode_layanan }} &middot; {{ $layanan->persyaratan->count() }} persyaratan</p>
                <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $layanan->deskripsi ?: 'Belum ada deskripsi.' }}</p>
            </a>
            @empty
            <div class="soft-card col-span-full p-10 text-center text-sm text-slate-500">
                Belum ada layanan yang ditugaskan ke seksi Anda. Hubungi admin untuk menetapkan seksi
                penanggung jawab pada layanan terkait.
            </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $layanans->links() }}
        </div>
    </div>
</x-app-layout>
