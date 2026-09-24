<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Permohonan Langsung</h2>
    </x-slot>

    <div class="space-y-4 py-2">
        <p class="text-sm text-slate-500">Untuk pemohon yang datang langsung ke loket. Pilih seksi, lalu pilih layanan yang ingin diajukan.</p>

        <div class="space-y-3" x-data="{ openUnit: null }">
            @foreach($seksis as $seksi)
            <div class="soft-card overflow-hidden">
                <button type="button" @click="openUnit = (openUnit === {{ $seksi->id }}) ? null : {{ $seksi->id }}"
                        class="flex w-full items-center justify-between gap-4 p-5 text-left transition hover:bg-emerald-50/40">
                    <div class="min-w-0">
                        <h3 class="font-semibold text-slate-900">{{ $seksi->nama_seksi }}</h3>
                        <p class="mt-1 text-xs font-medium text-emerald-700">{{ $seksi->layananList->count() }} layanan</p>
                    </div>
                    <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform duration-200"
                         :class="openUnit === {{ $seksi->id }} ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="openUnit === {{ $seksi->id }}" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="border-t border-slate-100 bg-slate-50/50 p-5">
                    @if($seksi->layananList->isEmpty())
                        <p class="mb-4 text-sm text-slate-400">Belum ada layanan di unit ini.</p>
                    @else
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($seksi->layananList as $layanan)
                            <a href="{{ route('petugas.permohonan.offline.create', $layanan) }}"
                               class="soft-card block bg-white p-4 transition hover:border-emerald-300 hover:shadow-md">
                                <p class="text-sm font-semibold text-slate-900">{{ $layanan->nama_layanan }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $layanan->persyaratan->count() }} persyaratan</p>
                            </a>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ route('petugas.permohonan.offline.manual.create', $seksi) }}"
                       class="mt-3 flex items-center gap-2 rounded-xl border border-dashed border-emerald-300 bg-white px-4 py-3 text-sm font-semibold text-emerald-700 transition hover:border-emerald-400 hover:bg-emerald-50">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Layanan tidak ada di daftar? Isi manual
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
