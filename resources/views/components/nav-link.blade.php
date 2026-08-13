@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-lg bg-emerald-50 px-3 py-2 text-sm font-semibold leading-5 text-emerald-700 ring-1 ring-emerald-200 transition duration-200'
            : 'inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium leading-5 text-slate-600 transition duration-200 hover:bg-emerald-50 hover:text-emerald-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
