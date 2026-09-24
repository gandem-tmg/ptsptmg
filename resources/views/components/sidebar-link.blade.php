@props(['href', 'active' => false])

<a href="{{ $href }}"
   title="{{ $slot }}"
   {{ $attributes->merge(['class' => 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150 ' .
        ($active
            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-md shadow-emerald-600/30'
            : 'text-slate-600 hover:translate-x-0.5 hover:bg-emerald-50 hover:text-emerald-700')
   ]) }}>
    <span class="shrink-0 {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-emerald-500' }}">
        {{ $icon ?? '' }}
    </span>
    <span class="sidebar-label truncate">{{ $slot }}</span>
</a>
