<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Monitoring Pelayanan Terpadu</h2>
    </x-slot>

    <div class="space-y-5 py-2">
        <!-- Ringkasan per seksi -->
        <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4">
            @foreach($seksis as $seksi)
            <a href="{{ route('pimpinan.monitoring.index', ['seksi_id' => $seksi->id]) }}"
               class="rounded-2xl border bg-white p-4 transition hover:shadow-md {{ request('seksi_id') == $seksi->id ? 'border-emerald-400 ring-1 ring-emerald-100' : 'border-slate-200' }}">
                <p class="truncate text-xs font-medium uppercase tracking-wide text-slate-500">{{ $seksi->nama_seksi }}</p>
                <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $seksi->permohonanAktif()->count() }}</p>
                <p class="text-xs text-slate-400">sedang ditangani</p>
            </a>
            @endforeach
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-sm font-semibold text-slate-900">Daftar Permohonan</h3>
                @if(request()->hasAny(['seksi_id', 'status']))
                    <a href="{{ route('pimpinan.monitoring.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Reset filter</a>
                @endif
            </div>

            <form method="GET" action="{{ route('pimpinan.monitoring.index') }}" class="mb-5 flex flex-wrap gap-3">
                <select name="seksi_id" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200" onchange="this.form.submit()">
                    <option value="">Semua Seksi</option>
                    @foreach($seksis as $seksi)
                        <option value="{{ $seksi->id }}" {{ request('seksi_id') == $seksi->id ? 'selected' : '' }}>{{ $seksi->nama_seksi }}</option>
                    @endforeach
                </select>
                <select name="status" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Permohonan::STATUS_LABELS as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>

            @if($permohonans->isEmpty())
                <div class="rounded-xl bg-slate-50 p-8 text-center text-sm text-slate-500">Tidak ada data.</div>
            @else
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($permohonans as $permohonan)
                        <x-permohonan-card :permohonan="$permohonan" route-prefix="pimpinan" :show-seksi="true" />
                    @endforeach
                </div>

                <div class="mt-5">
                    {{ $permohonans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
