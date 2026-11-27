@props(['size' => 'md'])

@php
    $sizeClass = match ($size) {
        'sm' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        default => 'w-9 h-9',
    };
@endphp

<span {{ $attributes->merge(['class' => "{$sizeClass} inline-flex items-center justify-center rounded-xl bg-meadow-50 border border-meadow-200/80 shadow-sm shrink-0"]) }}>
    <svg class="w-4/5 h-4/5" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <!-- Isometric Voxel Top (Meadow Grass) -->
        <polygon points="16,3 28,9.5 16,16 4,9.5" fill="#6BAF5D" />
        <polygon points="16,3 28,9.5 16,12.5 4,9.5" fill="#7FC072" />
        <!-- Isometric Left Face (Earth Dirt + Grass Rim) -->
        <polygon points="4,9.5 16,16 16,29 4,22.5" fill="#B57A4E" />
        <polygon points="4,9.5 16,16 16,19.5 12,17.5 12,19 8,17 8,15.5 4,13.5" fill="#559648" />
        <!-- Isometric Right Face (Shaded Earth + Grass Rim) -->
        <polygon points="16,16 28,9.5 28,22.5 16,29" fill="#9A643D" />
        <polygon points="16,16 28,9.5 28,13.5 24,15.5 24,17 20,19 20,17.5 16,19.5" fill="#417637" />
        <!-- Minimal Cream Tent-Peak Accent on Top Face -->
        <path d="M11.5 11.2L16 6.8L20.5 11.2" stroke="#F5E6D3" stroke-width="1.8" stroke-linecap="square" stroke-linejoin="miter" />
    </svg>
</span>
