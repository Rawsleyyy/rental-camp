<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-bold text-ink leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div data-aos="fade-up" class="bg-white rounded-3xl border border-canvas-border overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="p-8 text-ink">
                {{ __("You're logged in!") }}
            </div>
        </div>
    </div>
</x-app-layout>
