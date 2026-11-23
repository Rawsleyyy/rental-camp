<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-2.5 bg-neutral-950 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-400 hover:text-neutral-950 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 transition duration-200 shadow-sm']) }}>
    {{ $slot }}
</button>
