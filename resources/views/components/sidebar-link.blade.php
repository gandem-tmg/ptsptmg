@props(['href', 'active' => false])

<a href="{{ $href }}"
   title="{{ $slot }}"
   {{ $attributes->merge(['class' => 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors ' .
        ($active
            ? 'bg-emerald-50 text-emerald-700'
            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900')
   ]) }}>
    <span class="shrink-0 {{ $active ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-500' }}">
        {{ $icon ?? '' }}
    </span>
    <span class="sidebar-label truncate">{{ $slot }}</span>
</a>
