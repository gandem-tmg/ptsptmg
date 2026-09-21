@props(['seksis' => null, 'resetRoute'])

@php
    // Sumber tunggal: App\Models\Permohonan::STATUS_LABELS — supaya filter
    // ini selalu ikut lengkap kalau ada status baru ditambahkan, tidak
    // pernah "bolong" cuma nampilin sebagian status lama/baru.
    $statusOptions = \App\Models\Permohonan::STATUS_LABELS;
    $adaFilterAktif = request()->filled('status') || request()->filled('seksi_id')
        || request()->filled('tanggal_dari') || request()->filled('tanggal_sampai');
    $statusTerpilih = request('status');
@endphp

<div class="mt-3 grid grid-cols-1 gap-2.5 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
    <select name="status" class="w-full min-w-0 rounded-xl border-slate-200 py-2.5 pl-3 pr-8 text-sm focus:border-emerald-500 focus:ring-emerald-200 sm:w-56">
        <option value="">Semua Status</option>
        @foreach($statusOptions as $value => $label)
            <option value="{{ $value }}" @selected(!is_array($statusTerpilih) && $statusTerpilih === $value)>{{ $label }}</option>
        @endforeach
    </select>

    @if($seksis)
        <select name="seksi_id" class="w-full min-w-0 rounded-xl border-slate-200 py-2.5 pl-3 pr-8 text-sm focus:border-emerald-500 focus:ring-emerald-200 sm:w-52">
            <option value="">Semua Seksi</option>
            @foreach($seksis as $seksi)
                <option value="{{ $seksi->id }}" @selected((string) request('seksi_id') === (string) $seksi->id)>{{ $seksi->nama_seksi }}</option>
            @endforeach
        </select>
    @endif

    <div class="grid grid-cols-2 gap-2.5 sm:flex sm:items-center sm:gap-2">
        <label class="flex flex-col gap-1 text-xs font-medium text-slate-500 sm:flex-row sm:items-center sm:gap-1.5">
            <span class="sm:whitespace-nowrap">Dari</span>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                   class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-200 sm:w-40">
        </label>
        <label class="flex flex-col gap-1 text-xs font-medium text-slate-500 sm:flex-row sm:items-center sm:gap-1.5">
            <span class="sm:whitespace-nowrap">Sampai</span>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                   class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-200 sm:w-40">
        </label>
    </div>

    <div class="flex items-center justify-between gap-3 sm:ml-auto sm:justify-end">
        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 sm:hidden">
            Terapkan Filter
        </button>
        @if($adaFilterAktif)
            <a href="{{ $resetRoute }}" class="text-xs font-semibold text-slate-400 hover:text-slate-600">Reset Filter</a>
        @endif
    </div>
</div>
