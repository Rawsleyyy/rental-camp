<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Rawlseyyy — Persewaan Alat Camping & Outdoor' }}</title>

        <!-- Fonts: Playfair Display & Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
    <body class="font-sans antialiased bg-neutral-50 text-neutral-900 selection:bg-amber-400 selection:text-neutral-950 min-h-screen flex flex-col">
        @php
            $cartCount = Auth::check() ? (int) Auth::user()->carts()->sum('quantity') : 0;
        @endphp

        <!-- Public Storefront Navbar -->
        <header x-data="{ open: false }" class="sticky top-0 inset-x-0 z-50 bg-neutral-950/95 backdrop-blur-md text-white border-b border-white/10 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 gap-4">
                    <!-- Brand -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                        <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-white/10 border border-white/15 text-amber-400 group-hover:bg-amber-400 group-hover:text-neutral-950 transition">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 20h18L12 4 3 20z" />
                                <path d="M12 4v16" />
                                <path d="M9.5 20L12 13l2.5 7" />
                            </svg>
                        </span>
                        <span class="text-xl font-bold tracking-tight text-white">Rawlseyyy</span>
                    </a>

                    <!-- Center Links (Desktop) -->
                    <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-neutral-300">
                        <a href="{{ route('home') }}" class="hover:text-white transition {{ request()->routeIs('home') ? 'text-amber-400 font-semibold' : '' }}">Home</a>
                        <a href="{{ route('katalog') }}" class="hover:text-white transition {{ request()->routeIs('katalog') ? 'text-amber-400 font-semibold' : '' }}">Katalog</a>
                        <a href="{{ route('artikel.index') }}" class="hover:text-white transition {{ request()->routeIs('artikel.*') ? 'text-amber-400 font-semibold' : '' }}">Artikel</a>
                        <a href="{{ route('cek-booking') }}" class="hover:text-white transition {{ request()->routeIs('cek-booking') ? 'text-amber-400 font-semibold' : '' }}">Cek Booking</a>
                        <a href="{{ route('statistik') }}" class="hover:text-white transition {{ request()->routeIs('statistik') ? 'text-amber-400 font-semibold' : '' }}">Statistik</a>
                    </nav>

                    <!-- Right Actions (Desktop) -->
                    <div class="hidden lg:flex items-center gap-3 shrink-0">
                        <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 text-xs font-semibold text-white transition">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Keranjang</span>
                            @if ($cartCount > 0)
                                <span class="px-1.5 py-0.5 rounded-full bg-amber-400 text-neutral-950 text-[10px] font-bold leading-none">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>

                        @auth
                            @if (in_array(Auth::user()->role, ['admin', 'staff'], true))
                                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-full bg-white text-neutral-950 text-xs font-semibold hover:bg-amber-400 transition">
                                    Workspace {{ ucfirst(Auth::user()->role) }}
                                </a>
                            @else
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-neutral-950 text-xs font-semibold hover:bg-amber-400 transition">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-5 h-5 rounded-full object-cover">
                                    @endif
                                    <span>Profil &amp; Sewa Saya</span>
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-xs font-medium text-neutral-300 hover:text-white transition">
                                    Log Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-medium text-neutral-200 hover:text-white transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-white text-neutral-950 text-xs font-semibold hover:bg-amber-400 transition">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Mobile Toggle -->
                    <div class="flex lg:hidden items-center gap-2">
                        <a href="{{ route('cart.index') }}" class="relative p-2 rounded-lg text-neutral-200 hover:bg-white/10 transition" aria-label="Keranjang">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if ($cartCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 px-1.5 py-0.5 rounded-full bg-amber-400 text-neutral-950 text-[10px] font-bold leading-none">{{ $cartCount }}</span>
                            @endif
                        </a>

                        <button type="button" @click="open = !open" class="p-2 rounded-lg text-neutral-300 hover:text-white hover:bg-white/10 transition" aria-label="Menu">
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
                <div x-show="open" x-transition x-cloak class="lg:hidden pb-5 pt-2 border-t border-white/10 space-y-3">
                    <nav class="flex flex-col space-y-1 text-sm font-medium text-neutral-300">
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 hover:text-white">Home</a>
                        <a href="{{ route('katalog') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 hover:text-white">Katalog</a>
                        <a href="{{ route('artikel.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 hover:text-white">Artikel</a>
                        <a href="{{ route('cek-booking') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 hover:text-white">Cek Booking</a>
                        <a href="{{ route('statistik') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 hover:text-white">Statistik</a>
                        <a href="{{ route('cart.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 hover:text-white">Keranjang ({{ $cartCount }})</a>
                    </nav>

                    <div class="pt-3 border-t border-white/10 flex flex-col gap-2">
                        @auth
                            @if (in_array(Auth::user()->role, ['admin', 'staff'], true))
                                <a href="{{ route('dashboard') }}" class="w-full text-center px-4 py-2.5 rounded-full bg-white text-neutral-950 text-sm font-semibold">Workspace {{ ucfirst(Auth::user()->role) }}</a>
                            @else
                                <a href="{{ route('profile.edit') }}" class="w-full text-center px-4 py-2.5 rounded-full bg-white text-neutral-950 text-sm font-semibold">Profil &amp; Sewa Saya</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-center px-4 py-2.5 rounded-full border border-white/20 text-white text-sm font-medium">Log Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="w-full text-center px-4 py-2.5 rounded-full border border-white/20 text-white text-sm font-medium">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 rounded-full bg-white text-neutral-950 text-sm font-semibold">Register</a>
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
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-medium text-emerald-900">
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
        <footer id="kontak" class="bg-neutral-950 text-neutral-400 py-12 border-t border-white/10 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-white/10">
                    <div class="text-center md:text-left">
                        <span class="text-lg font-bold tracking-tight text-white">Rawlseyyy</span>
                        <p class="mt-1 text-xs text-neutral-400">Persewaan Alat Camping &amp; Outdoor Terpercaya &middot; Basecamp Jl. Pinus Raya No. 24</p>
                    </div>
                    <nav class="flex flex-wrap items-center justify-center gap-6 text-xs font-medium">
                        <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                        <a href="{{ route('katalog') }}" class="hover:text-white transition">Katalog</a>
                        <a href="{{ route('artikel.index') }}" class="hover:text-white transition">Artikel</a>
                        <a href="{{ route('cek-booking') }}" class="hover:text-white transition">Cek Booking</a>
                        <a href="{{ route('statistik') }}" class="hover:text-white transition">Statistik</a>
                    </nav>
                </div>
                <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-neutral-500">
                    <p>&copy; 2026 Rawlseyyy. All rights reserved.</p>
                    <p>WhatsApp Basecamp: 0812-3456-7890 &bull; Buka Setiap Hari 08.00 – 21.00 WIB</p>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
