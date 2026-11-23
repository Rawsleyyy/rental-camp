@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-neutral-300 bg-neutral-50/70 focus:bg-white focus:border-neutral-950 focus:ring-neutral-950 rounded-xl shadow-sm text-sm py-2.5 px-3.5 transition']) }}>
