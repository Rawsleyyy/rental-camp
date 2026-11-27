@props(['size' => 'md'])

@php
    $sizeClass = match ($size) {
        'sm' => 'w-9 h-9',
        'lg' => 'w-20 h-20',
        default => 'w-10 h-10',
    };
@endphp

<span {{ $attributes->merge(['class' => "{$sizeClass} inline-flex items-center justify-center shrink-0 drop-shadow-sm"]) }}>
    <img
        src="{{ asset('images/logo.png') }}"
        alt="Pack &amp; Pitch"
        class="w-full h-full object-contain"
    >
</span>
