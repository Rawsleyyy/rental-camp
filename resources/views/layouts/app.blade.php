<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pack & Pitch') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('favicon.png?v=2') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png?v=2') }}">

        <!-- Fonts: Outfit & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

        <!-- AOS (Animate On Scroll) CSS -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            html,
            body {
                overflow-x: clip;
            }
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-canvas-warm text-ink selection:bg-meadow-500 selection:text-white">
        @php
            $role = Auth::user()->role ?? 'user';
            $roleBadgeClass = match ($role) {
                'admin' => 'bg-meadow-100 text-meadow-700 border border-meadow-200',
                'staff' => 'bg-earth-100 text-earth-700 border border-earth-200',
                default => 'bg-skyblock-100 text-skyblock-700 border border-skyblock-200',
            };
        @endphp

        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-canvas-warm flex">
            <!-- Fixed Left Sidebar & Mobile Slide-Over -->
            @include('layouts.navigation')

            <!-- Main Content Column -->
            <div class="flex-1 flex flex-col min-w-0 lg:pl-72">
                <!-- Slim Topbar -->
                <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-canvas-border h-16 px-4 sm:px-6 lg:px-10 flex items-center justify-between gap-4">
                    <!-- Left: Mobile Sidebar Toggle & Workspace Title -->
                    <div class="flex items-center gap-3 min-w-0">
                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            class="lg:hidden inline-flex items-center justify-center p-2 rounded-xl text-ink-muted hover:text-ink hover:bg-meadow-50 focus:outline-none transition"
                            aria-label="Buka Sidebar Menu"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        @isset($header)
                            <div class="truncate">
                                {{ $header }}
                            </div>
                        @else
                            <div class="flex items-center gap-2.5 truncate">
                                <span class="w-2.5 h-2.5 rounded-full bg-meadow-500 shrink-0"></span>
                                <span class="text-sm font-semibold text-ink truncate">
                                    Workspace {{ ucfirst($role) }} &middot; Pack &amp; Pitch
                                </span>
                            </div>
                        @endisset
                    </div>

                    <!-- Right: Storefront Link, Role Badge & User Dropdown -->
                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ url('/') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-meadow-500/40 bg-white text-xs font-semibold text-meadow-700 hover:bg-meadow-50 transition">
                            <span>Lihat Beranda</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>

                        <span class="hidden md:inline-flex px-3 py-1 rounded-full text-[11px] font-semibold uppercase tracking-widest {{ $roleBadgeClass }}">
                            {{ $role }}
                        </span>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-meadow-500 text-white hover:bg-meadow-600 focus:outline-none transition shadow-sm shadow-meadow-500/20">
                                    <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                    <svg class="fill-current h-4 w-4 opacity-80" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profil Saya') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 py-8 px-4 sm:px-6 lg:px-10 max-w-7xl w-full mx-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- AOS (Animate On Scroll) JS & Global Init -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        once: true,
                        duration: 800,
                    });
                }
            });
        </script>
        @stack('scripts')
    </body>
</html>
