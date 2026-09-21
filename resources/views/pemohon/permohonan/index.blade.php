<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-slate-900">Permohonan Saya</h2>
            {{-- Disembunyikan di mobile: sudah ada shortcut "Ajukan" di bottom nav, biar tidak terlalu padat --}}
            <a href="{{ route('layanan.index') }}" class="primary-btn hidden text-xs sm:inline-flex sm:text-sm">
                + Ajukan Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 py-2">
        @if(session('warning'))
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">{{ session('warning') }}</div>
        @endif
        @if(session('success'))
            <div class="flash-banner rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @error('batal')
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ $message }}</div>
        @enderror

        <form method="GET" action="{{ route('pemohon.permohonan.index') }}">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no tiket / layanan..."
                       class="flex-1 rounded-lg border-slate-300 px-3 py-2 text-[13px] transition-colors duration-150 focus:border-emerald-500 focus:ring-emerald-200">
                <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Cari</button>
            </div>
            <x-permohonan-filter-bar :reset-route="route('pemohon.permohonan.index')" />
        </form>

        @if($permohonans->isEmpty())
            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
                Anda belum punya permohonan.
                <a href="{{ route('layanan.index') }}" class="font-semibold text-emerald-600 hover:text-emerald-700">Lihat layanan tersedia &rarr;</a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($permohonans as $permohonan)
                    <x-permohonan-card :permohonan="$permohonan" route-prefix="pemohon" :show-seksi="true">
                        <x-slot:actions>
                            <a href="{{ route('pemohon.permohonan.pdf', $permohonan) }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                                Unduh Bukti
                            </a>
                            @if(in_array($permohonan->status, ['diajukan', 'didisposisikan']))
                                <form method="POST" action="{{ route('pemohon.permohonan.batalkan', $permohonan) }}" class="ml-auto" onsubmit="return confirm('Batalkan permohonan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-700">Batalkan</button>
                                </form>
                            @elseif($permohonan->status === 'selesai' && !$permohonan->surveiRespon)
                                <a href="{{ config('skm.external_url') }}" target="_blank" rel="noopener" class="ml-auto text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                                    Isi Survei Kepuasan
                                </a>
                            @endif
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
