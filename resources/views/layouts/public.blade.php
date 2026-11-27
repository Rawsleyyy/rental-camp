<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Rawlseyyy — Persewaan Alat Camping & Outdoor' }}</title>

        <!-- Fonts: Outfit & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

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
    <body class="font-sans antialiased bg-canvas-warm text-ink selection:bg-meadow-500 selection:text-white min-h-screen flex flex-col">
        @php
            $isNonRenter = Auth::check() && in_array(Auth::user()->role, ['admin', 'staff'], true);
            $cartCount = (Auth::check() && ! $isNonRenter) ? (int) Auth::user()->carts()->sum('quantity') : 0;
        @endphp

        <!-- Public Storefront Navbar -->
        <header x-data="{ open: false }" class="sticky top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-md text-ink border-b border-canvas-border shadow-[0_4px_20px_-8px_rgba(43,43,43,0.08)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 gap-4">
                    <!-- Brand -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                        <x-brand-mark size="md" class="group-hover:border-meadow-500 transition" />
                        <span class="font-display text-xl font-bold tracking-tight text-ink">Rawlseyyy</span>
                    </a>

                    <!-- Center Links (Desktop) -->
                    <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-ink-muted">
                        <a href="{{ route('home') }}" class="hover:text-meadow-600 transition {{ request()->routeIs('home') ? 'text-meadow-600 font-semibold' : '' }}">Home</a>
                        <a href="{{ route('katalog') }}" class="hover:text-meadow-600 transition {{ request()->routeIs('katalog') ? 'text-meadow-600 font-semibold' : '' }}">Katalog</a>
                        <a href="{{ route('artikel.index') }}" class="hover:text-meadow-600 transition {{ request()->routeIs('artikel.*') ? 'text-meadow-600 font-semibold' : '' }}">Artikel</a>
                        <a href="{{ route('cek-booking') }}" class="hover:text-meadow-600 transition {{ request()->routeIs('cek-booking') ? 'text-meadow-600 font-semibold' : '' }}">Cek Booking</a>
                        <a href="{{ route('statistik') }}" class="hover:text-meadow-600 transition {{ request()->routeIs('statistik') ? 'text-meadow-600 font-semibold' : '' }}">Statistik</a>
                    </nav>

                    <!-- Right Actions (Desktop) -->
                    <div class="hidden lg:flex items-center gap-3 shrink-0">
                        @if (! $isNonRenter)
                            <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border border-canvas-border bg-canvas-warm hover:border-meadow-400 hover:bg-meadow-50 text-xs font-semibold text-ink transition">
                                <svg class="w-4 h-4 text-meadow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Keranjang</span>
                                @if ($cartCount > 0)
                                    <span class="px-1.5 py-0.5 rounded-full bg-meadow-500 text-white text-[10px] font-bold leading-none">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        @endif

                        @auth
                            @if (in_array(Auth::user()->role, ['admin', 'staff'], true))
                                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                    Workspace {{ ucfirst(Auth::user()->role) }} &nearr;
                                </a>
                            @else
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-5 h-5 rounded-full object-cover ring-1 ring-white/70">
                                    @endif
                                    <span>Profil &amp; Sewa Saya</span>
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-xs font-medium text-ink-muted hover:text-red-600 transition">
                                    Log Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-medium text-ink-muted hover:text-meadow-700 transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Mobile Toggle -->
                    <div class="flex lg:hidden items-center gap-2">
                        @if (! $isNonRenter)
                            <a href="{{ route('cart.index') }}" class="relative p-2 rounded-xl text-ink hover:bg-meadow-50 transition" aria-label="Keranjang">
                                <svg class="w-5 h-5 text-meadow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                @if ($cartCount > 0)
                                    <span class="absolute -top-0.5 -right-0.5 px-1.5 py-0.5 rounded-full bg-meadow-500 text-white text-[10px] font-bold leading-none">{{ $cartCount }}</span>
                                @endif
                            </a>
                        @endif

                        <button type="button" @click="open = !open" class="p-2 rounded-xl text-ink-muted hover:text-ink hover:bg-canvas-warm transition" aria-label="Menu">
                            <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div x-show="open" x-transition x-cloak class="lg:hidden pb-5 pt-3 border-t border-canvas-border bg-white space-y-3">
                    <nav class="flex flex-col space-y-1 text-sm font-medium text-ink-muted">
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl hover:bg-meadow-50 hover:text-meadow-700 {{ request()->routeIs('home') ? 'bg-meadow-50 text-meadow-700 font-semibold' : '' }}">Home</a>
                        <a href="{{ route('katalog') }}" class="px-3 py-2 rounded-xl hover:bg-meadow-50 hover:text-meadow-700 {{ request()->routeIs('katalog') ? 'bg-meadow-50 text-meadow-700 font-semibold' : '' }}">Katalog</a>
                        <a href="{{ route('artikel.index') }}" class="px-3 py-2 rounded-xl hover:bg-meadow-50 hover:text-meadow-700 {{ request()->routeIs('artikel.*') ? 'bg-meadow-50 text-meadow-700 font-semibold' : '' }}">Artikel</a>
                        <a href="{{ route('cek-booking') }}" class="px-3 py-2 rounded-xl hover:bg-meadow-50 hover:text-meadow-700 {{ request()->routeIs('cek-booking') ? 'bg-meadow-50 text-meadow-700 font-semibold' : '' }}">Cek Booking</a>
                        <a href="{{ route('statistik') }}" class="px-3 py-2 rounded-xl hover:bg-meadow-50 hover:text-meadow-700 {{ request()->routeIs('statistik') ? 'bg-meadow-50 text-meadow-700 font-semibold' : '' }}">Statistik</a>
                        @if (! $isNonRenter)
                            <a href="{{ route('cart.index') }}" class="px-3 py-2 rounded-xl hover:bg-meadow-50 hover:text-meadow-700">Keranjang ({{ $cartCount }})</a>
                        @endif
                    </nav>

                    <div class="pt-3 border-t border-canvas-border flex flex-col gap-2">
                        @auth
                            @if (in_array(Auth::user()->role, ['admin', 'staff'], true))
                                <a href="{{ route('dashboard') }}" class="w-full text-center px-4 py-2.5 rounded-full bg-meadow-500 text-white text-sm font-semibold">Workspace {{ ucfirst(Auth::user()->role) }}</a>
                            @else
                                <a href="{{ route('profile.edit') }}" class="w-full text-center px-4 py-2.5 rounded-full bg-meadow-500 text-white text-sm font-semibold">Profil &amp; Sewa Saya</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-center px-4 py-2.5 rounded-full border border-canvas-border text-ink-muted hover:text-red-600 text-sm font-medium">Log Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="w-full text-center px-4 py-2.5 rounded-full border border-meadow-500/60 text-meadow-700 text-sm font-semibold">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 rounded-full bg-meadow-500 text-white text-sm font-semibold">Register</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Global Flash Messages -->
        @if (session('success') || session('error'))
            <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-6">
                @if (session('success'))
                    <div class="rounded-2xl border border-meadow-200 bg-meadow-50 px-5 py-3.5 text-sm font-medium text-meadow-900">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-3.5 text-sm font-medium text-red-900">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer id="kontak" class="bg-white text-ink-muted py-12 border-t border-canvas-border mt-16 relative">
            <div class="absolute top-0 inset-x-0 h-1 palette-strip"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-canvas-border">
                    <div class="flex items-center gap-3 text-center md:text-left">
                        <x-brand-mark size="sm" />
                        <div>
                            <span class="font-display text-lg font-bold tracking-tight text-ink">Rawlseyyy</span>
                            <p class="mt-0.5 text-xs text-ink-muted">Persewaan Alat Camping &amp; Outdoor Terpercaya &middot; Basecamp Jl. Pinus Raya No. 24</p>
                        </div>
                    </div>
                    <nav class="flex flex-wrap items-center justify-center gap-6 text-xs font-medium">
                        <a href="{{ route('home') }}" class="hover:text-meadow-600 transition">Home</a>
                        <a href="{{ route('katalog') }}" class="hover:text-meadow-600 transition">Katalog</a>
                        <a href="{{ route('artikel.index') }}" class="hover:text-meadow-600 transition">Artikel</a>
                        <a href="{{ route('cek-booking') }}" class="hover:text-meadow-600 transition">Cek Booking</a>
                        <a href="{{ route('statistik') }}" class="hover:text-meadow-600 transition">Statistik</a>
                    </nav>
                </div>
                <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-muted">
                    <p>&copy; 2026 Rawlseyyy. All rights reserved.</p>
                    <p>WhatsApp Basecamp: 0812-3456-7890 &bull; Buka Setiap Hari 08.00 – 21.00 WIB</p>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
