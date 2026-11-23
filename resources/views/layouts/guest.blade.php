<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rawlseyyy') }}</title>

        <!-- Fonts: Playfair Display & Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- AOS (Animate On Scroll) CSS -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            html,
            body {
                overflow-x: clip;
            }
        </style>
    </head>
    <body class="font-sans text-neutral-900 antialiased selection:bg-amber-400 selection:text-neutral-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 bg-neutral-950 relative overflow-hidden">
            <!-- Ambient Radial Glow -->
            <div class="pointer-events-none absolute -top-24 left-1/2 -translate-x-1/2 w-[520px] h-[520px] rounded-full bg-amber-500/10 blur-3xl"></div>

            <!-- Brand Header -->
            <div data-aos="fade-up" class="relative text-center">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-3 group">
                    <span class="flex items-center justify-center w-12 h-12 rounded-2xl bg-white/10 border border-white/15 text-amber-400 group-hover:bg-amber-400 group-hover:text-neutral-950 transition shadow-lg">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 20h18L12 4 3 20z" />
                            <path d="M12 4v16" />
                            <path d="M9.5 20L12 13l2.5 7" />
                        </svg>
                    </span>
                    <div>
                        <span class="block text-2xl font-bold tracking-tight text-white">Rawlseyyy</span>
                        <span class="block text-xs uppercase tracking-[0.2em] text-neutral-400 mt-1">Persewaan Alat Camping &amp; Outdoor</span>
                    </div>
                </a>
            </div>

            <!-- Auth Card -->
            <div data-aos="fade-up" data-aos-delay="100" class="relative w-full sm:max-w-md mt-7 px-7 py-8 bg-white shadow-2xl border border-neutral-200/80 overflow-hidden rounded-3xl">
                {{ $slot }}
            </div>

            <!-- Back to Home Link -->
            <div data-aos="fade-up" data-aos-delay="150" class="relative mt-6">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-400 hover:text-amber-400 transition">
                    &larr; Kembali ke Beranda Rawlseyyy
                </a>
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
    </body>
</html>
