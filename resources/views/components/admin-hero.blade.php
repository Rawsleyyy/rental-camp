@props([
    'eyebrow' => 'Rawlseyyy Console',
    'title',
    'subtitle' => '',
    'actionLabel' => null,
    'actionHref' => null,
])

<div data-aos="fade-up" class="relative overflow-hidden rounded-3xl bg-neutral-950 text-white p-8 sm:p-10 mb-8 shadow-xl border border-neutral-800/80">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(251,191,36,0.16),transparent_55%)] pointer-events-none"></div>
    <div class="relative flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div class="max-w-2xl">
            <p class="text-[11px] uppercase tracking-[0.25em] text-amber-400 font-semibold mb-3">
                {{ $eyebrow }}
            </p>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold tracking-tight text-white">
                {{ $title }}
            </h1>
            @if ($subtitle)
                <p class="mt-2.5 text-sm text-neutral-300 leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        @if ($actionLabel && $actionHref)
            <div class="shrink-0">
                <a href="{{ $actionHref }}"
                   class="inline-flex items-center gap-2 rounded-full bg-white text-neutral-950 hover:bg-amber-400 px-6 py-2.5 text-sm font-semibold transition shadow-sm">
                    {{ $actionLabel }}
                </a>
            </div>
        @endif
    </div>
</div>
