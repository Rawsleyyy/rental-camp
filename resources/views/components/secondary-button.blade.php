<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-white border border-meadow-500/60 rounded-full font-semibold text-xs text-meadow-700 tracking-wide shadow-sm hover:bg-meadow-50 focus:outline-none focus:ring-2 focus:ring-meadow-500 focus:ring-offset-2 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
