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
        </style>
    </head>
    <body class="font-sans text-ink antialiased selection:bg-meadow-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 bg-gradient-to-br from-skyblock-100 via-canvas to-earth-100/75 relative overflow-hidden">
            <!-- Subtle Scenic Mountain Horizon -->
            <img
                src="{{ asset('storage/hero/alpine-meadow.jpg') }}"
                alt=""
                aria-hidden="true"
                class="pointer-events-none absolute inset-x-0 bottom-0 w-full h-72 object-cover object-bottom opacity-15"
            >
            <div class="pointer-events-none absolute -top-24 left-1/4 w-[440px] h-[440px] rounded-full bg-skyblock-300/30 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-0 right-1/4 w-[380px] h-[380px] rounded-full bg-meadow-200/30 blur-3xl"></div>

            <!-- Brand Header -->
            <div data-aos="fade-up" class="relative text-center">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2.5 group">
                    <x-brand-mark size="lg" class="group-hover:scale-105 transition drop-shadow-md" />
                    <div>
                        <span class="block font-display text-3xl font-bold tracking-tight text-ink">Pack &amp; Pitch</span>
                        <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-earth-600 mt-1">Persewaan Alat Camping &amp; Outdoor</span>
                    </div>
                </a>
            </div>

            <!-- Auth Card -->
            <div data-aos="fade-up" data-aos-delay="100" class="relative w-full sm:max-w-md mt-7 bg-white/95 backdrop-blur-md shadow-[0_20px_50px_-20px_rgba(43,43,43,0.16)] border border-canvas-border overflow-hidden rounded-[2rem]">
                <div class="h-1.5 w-full palette-strip"></div>
                <div class="px-7 sm:px-8 py-8">
                    {{ $slot }}
                </div>
            </div>

            <!-- Back to Home Link -->
            <div data-aos="fade-up" data-aos-delay="150" class="relative mt-6">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-muted hover:text-meadow-700 transition">
                    &larr; Kembali ke Beranda Pack &amp; Pitch
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
