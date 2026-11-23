<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl font-bold text-neutral-950 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div data-aos="fade-up" class="bg-white rounded-3xl border border-neutral-200/80 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="p-8 text-neutral-900">
                {{ __("You're logged in!") }}
            </div>
        </div>
    </div>
</x-app-layout>
