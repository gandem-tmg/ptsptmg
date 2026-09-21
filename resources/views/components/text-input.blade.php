@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border border-slate-300 bg-white px-3 py-2 text-[13px] text-slate-900 shadow-sm transition-colors duration-150 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200']) }}>
