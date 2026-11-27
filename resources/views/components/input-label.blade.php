@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-xs uppercase tracking-wider text-ink-muted']) }}>
    {{ $value ?? $slot }}
</label>
