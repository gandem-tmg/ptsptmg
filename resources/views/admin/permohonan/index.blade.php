<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Semua Permohonan</h2>
    </x-slot>

    <div class="space-y-4 py-2">
        @if(session('success'))
            <div class="flash-banner rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.permohonan.index') }}">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no tiket / nama pemohon / layanan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
            <x-permohonan-filter-bar :seksis="$seksis" :reset-route="route('admin.permohonan.index')" />
        </form>

        @if($permohonans->isEmpty())
            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">Belum ada permohonan.</div>
        @else
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($permohonans as $permohonan)
                    <x-permohonan-card :permohonan="$permohonan" route-prefix="admin" :show-seksi="true">
                        <x-slot:actions>
                            <a href="{{ route('admin.permohonan.edit', $permohonan) }}" title="Edit" class="icon-action icon-action-edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                        </x-slot:actions>
                    </x-permohonan-card>
                @endforeach
            </div>

            <div class="flex items-center justify-between pt-2 text-sm text-slate-500">
                <span>{{ $permohonans->firstItem() }}–{{ $permohonans->lastItem() }} dari {{ $permohonans->total() }}</span>
                {{ $permohonans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
