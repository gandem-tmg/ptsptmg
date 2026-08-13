@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-xl bg-emerald-50 px-3 py-2.5 text-start text-sm font-semibold text-emerald-700 ring-1 ring-emerald-200 transition duration-200'
            : 'block w-full rounded-xl px-3 py-2.5 text-start text-sm font-medium text-slate-600 transition duration-200 hover:bg-emerald-50 hover:text-emerald-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
