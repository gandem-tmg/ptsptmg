@props(['persyaratanList' => collect()])

<div id="persyaratan-rows" class="space-y-3">
    @foreach($persyaratanList as $i => $p)
    <div class="flex items-start gap-2 persyaratan-row">
        <input type="hidden" name="persyaratan[{{ $i }}][id]" value="{{ $p->id }}">
        <input type="text" name="persyaratan[{{ $i }}][nama_persyaratan]" value="{{ $p->nama_persyaratan }}"
               placeholder="Contoh: Fotokopi KTP"
               class="flex-1 rounded-lg border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
        <label class="flex shrink-0 items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-600">
            <input type="checkbox" name="persyaratan[{{ $i }}][wajib]" value="1" {{ $p->wajib ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
            Wajib
        </label>
        <button type="button" onclick="this.closest('.persyaratan-row').remove()" class="shrink-0 rounded-lg p-2 text-slate-400 hover:bg-rose-50 hover:text-rose-600">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
    @endforeach
</div>

<button type="button" onclick="tambahBarisPersyaratan()" class="mt-3 text-sm font-semibold text-emerald-600 hover:text-emerald-700">
    + Tambah Persyaratan
</button>

<script>
    let persyaratanIndex = {{ $persyaratanList->count() }};

    function tambahBarisPersyaratan() {
        const container = document.getElementById('persyaratan-rows');
        const div = document.createElement('div');
        div.className = 'flex items-start gap-2 persyaratan-row';
        div.innerHTML = `
            <input type="text" name="persyaratan[${persyaratanIndex}][nama_persyaratan]" placeholder="Contoh: Fotokopi KTP"
                   class="flex-1 rounded-lg border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
            <label class="flex shrink-0 items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-600">
                <input type="checkbox" name="persyaratan[${persyaratanIndex}][wajib]" value="1" checked class="rounded border-slate-300 text-emerald-600">
                Wajib
            </label>
            <button type="button" onclick="this.closest('.persyaratan-row').remove()" class="shrink-0 rounded-lg p-2 text-slate-400 hover:bg-rose-50 hover:text-rose-600">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        `;
        container.appendChild(div);
        persyaratanIndex++;
    }
</script>
