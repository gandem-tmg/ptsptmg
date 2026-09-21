<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-lg bg-rose-600 px-3.5 py-2 text-[13px] font-semibold text-white shadow-sm transition-all duration-150 hover:bg-rose-700 active:scale-[.97] focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
