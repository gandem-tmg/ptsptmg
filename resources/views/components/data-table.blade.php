@props(['empty' => false, 'emptyMessage' => 'Belum ada data.'])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200/70 bg-white shadow-md shadow-slate-200/60']) }}>
    @if($empty)
        <div class="p-10 text-center text-sm text-slate-500">{{ $emptyMessage }}</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                {{ $slot }}
            </table>
        </div>
    @endif
</div>
