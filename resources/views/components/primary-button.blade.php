<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-1.5 px-6 py-2.5 bg-meadow-500 border border-transparent rounded-full font-semibold text-xs text-white tracking-wide hover:bg-meadow-600 active:bg-meadow-700 focus:outline-none focus:ring-2 focus:ring-meadow-500 focus:ring-offset-2 transition duration-200 shadow-sm shadow-meadow-500/20']) }}>
    {{ $slot }}
</button>
