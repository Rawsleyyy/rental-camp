@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-xs uppercase tracking-wider text-neutral-600']) }}>
    {{ $value ?? $slot }}
</label>
