@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-1.5 block text-[13px] font-medium text-slate-600']) }}>
    {{ $value ?? $slot }}
</label>
