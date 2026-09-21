<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-[13px] font-semibold text-slate-700 shadow-sm transition-all duration-150 hover:border-slate-400 hover:bg-slate-50 active:scale-[.97] focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 disabled:opacity-40']) }}>
    {{ $slot }}
</button>
