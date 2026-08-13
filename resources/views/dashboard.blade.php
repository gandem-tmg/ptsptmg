<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mb-6 overflow-hidden rounded-[28px] bg-gradient-to-r from-emerald-600 via-emerald-500 to-cyan-500 p-5 text-white shadow-xl shadow-emerald-200 sm:p-7">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-100">Selamat datang</p>
                    <h3 class="mt-2 text-2xl font-bold">{{ Auth::user()->name }}</h3>
                </div>
                <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3 backdrop-blur-sm">
                    <p class="text-sm text-emerald-50">Status akun</p>
                    <p class="text-base font-semibold capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="soft-card p-5 sm:p-6">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Statistik Dashboard</h3>
                        <p class="text-sm text-slate-500">Ringkasan aktivitas layanan Anda</p>
                    </div>
                </div>

                @if(auth()->user()->role === 'pemohon')
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @php
                            $stats = [
                                ['label' => 'Total Layanan', 'value' => $totalLayanan, 'color' => 'blue', 'icon' => '<svg ...></svg>'],
                                ['label' => 'Permohonan Saya', 'value' => $totalPermohonan, 'color' => 'green', 'icon' => '<svg ...></svg>'],
                                ['label' => 'Diajukan', 'value' => $permohonanDiajukan, 'color' => 'yellow', 'icon' => '<svg ...></svg>'],
                                ['label' => 'Selesai', 'value' => $permohonanSelesai, 'color' => 'purple', 'icon' => '<svg ...></svg>'],
                            ];
                        @endphp

                        @foreach ($stats as $stat)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="rounded-xl bg-{{ $stat['color'] }}-100 p-3 text-{{ $stat['color'] }}-600">
                                        {!! $stat['icon'] !!}
                                    </div>
                                    <span class="rounded-full bg-white px-2 py-1 text-xs font-medium text-slate-500">Live</span>
                                </div>
                                <p class="mt-4 text-sm text-slate-500">{{ $stat['label'] }}</p>
                                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                        @php
                            $stats = [
                                ['label' => 'Total Layanan', 'value' => $totalLayanan, 'color' => 'blue', 'icon' => '<svg ...></svg>'],
                                ['label' => 'Total Permohonan', 'value' => $totalPermohonan, 'color' => 'green', 'icon' => '<svg ...></svg>'],
                                ['label' => 'Diajukan', 'value' => $permohonanDiajukan, 'color' => 'yellow', 'icon' => '<svg ...></svg>'],
                                ['label' => 'Selesai', 'value' => $permohonanSelesai, 'color' => 'purple', 'icon' => '<svg ...></svg>'],
                                ['label' => 'Ditolak', 'value' => $permohonanDitolak, 'color' => 'red', 'icon' => '<svg ...></svg>'],
                            ];
                        @endphp

                        @foreach ($stats as $stat)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="rounded-xl bg-{{ $stat['color'] }}-100 p-3 text-{{ $stat['color'] }}-600">
                                        {!! $stat['icon'] !!}
                                    </div>
                                    <span class="rounded-full bg-white px-2 py-1 text-xs font-medium text-slate-500">Live</span>
                                </div>
                                <p class="mt-4 text-sm text-slate-500">{{ $stat['label'] }}</p>
                                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('statistics.index') }}" class="primary-btn">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Lihat Statistik Lengkap
                        </a>
                    </div>
                @endif
            </div>

            <div class="soft-card p-5 sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">Fitur Tambahan</h3>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                        <div class="mb-3 inline-flex rounded-xl bg-emerald-100 p-3 text-emerald-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold text-emerald-900">Cari Tiket Permohonan</h4>
                        <p class="mt-2 text-sm leading-6 text-emerald-700">Cek status permohonan Anda dengan nomor tiket yang sudah diberikan.</p>
                        <button type="button" onclick="openSearchModal()" class="primary-btn mt-4 w-full sm:w-auto">
                            Cari Tiket
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="searchModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4">
            <div class="w-full max-w-md rounded-[28px] bg-white p-5 shadow-2xl sm:p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Cari Tiket Permohonan</h3>
                    <button type="button" onclick="closeSearchModal()" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">&times;</button>
                </div>

                <form method="POST" action="{{ route('guest.showTicket') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="modal_no_tiket" class="field-label">Nomor Tiket</label>
                        <input id="modal_no_tiket" type="text" name="no_tiket" required class="form-input" placeholder="Masukkan nomor tiket">
                        @error('no_tiket')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeSearchModal()" class="secondary-btn">Batal</button>
                        <button type="submit" class="primary-btn">Cari Tiket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openSearchModal() {
            const modal = document.getElementById('searchModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeSearchModal() {
            const modal = document.getElementById('searchModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>
