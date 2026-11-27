@props([
    'eyebrow' => 'Pack & Pitch Console',
    'title',
    'subtitle' => '',
    'actionLabel' => null,
    'actionHref' => null,
])

<div data-aos="fade-up" class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-skyblock-100/80 via-white to-earth-100/70 text-ink p-7 sm:p-9 mb-8 shadow-[0_12px_32px_-16px_rgba(43,43,43,0.12)] border border-canvas-border">
    <div class="absolute top-0 inset-x-0 h-1.5 grid grid-cols-4 pointer-events-none">
        <span class="bg-meadow-500"></span>
        <span class="bg-earth-500"></span>
        <span class="bg-earth-100"></span>
        <span class="bg-skyblock-300"></span>
    </div>
    <div class="absolute -top-10 -right-10 w-44 h-44 rounded-full bg-meadow-300/20 blur-2xl pointer-events-none"></div>

    <div class="relative flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 pt-1">
        <div class="max-w-2xl">
            <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-meadow-100 text-meadow-700 text-[11px] uppercase tracking-[0.2em] font-semibold mb-3">
                {{ $eyebrow }}
            </p>
            <h1 class="font-display text-2xl sm:text-4xl font-bold tracking-tight text-ink">
                {{ $title }}
            </h1>
            @if ($subtitle)
                <p class="mt-2 text-sm text-ink-muted leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        @if ($actionLabel && $actionHref)
            <div class="shrink-0">
                <a href="{{ $actionHref }}"
                   class="inline-flex items-center gap-2 rounded-full bg-meadow-500 text-white hover:bg-meadow-600 px-6 py-2.5 text-sm font-semibold transition shadow-sm shadow-meadow-500/20">
                    {{ $actionLabel }}
                </a>
            </div>
        @endif
    </div>
</div>
