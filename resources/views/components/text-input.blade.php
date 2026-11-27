@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-canvas-border bg-canvas-warm/70 text-ink placeholder:text-ink-muted/60 focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 rounded-xl shadow-sm text-sm py-2.5 px-3.5 transition']) }}>
