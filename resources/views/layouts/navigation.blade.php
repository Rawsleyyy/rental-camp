@php
    $role = Auth::user()->role ?? 'user';
    $dashboardRoute = match ($role) {
        'admin' => route('admin.dashboard'),
        'staff' => route('staff.dashboard'),
        default => route('user.dashboard'),
    };
@endphp

<!-- Mobile Backdrop -->
<div
    x-show="sidebarOpen"
    x-transition.opacity
    x-cloak
    @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-neutral-950/60 backdrop-blur-sm lg:hidden"
    aria-hidden="true"
></div>

<!-- Fixed Left Sidebar -->
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-neutral-950 text-white border-r border-white/10 flex flex-col transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-2xl"
>
    <!-- Brand Header -->
    <div class="h-16 px-6 border-b border-white/10 flex items-center justify-between shrink-0">
        <a href="{{ $dashboardRoute }}" class="flex items-center gap-3 group">
            <span class="w-9 h-9 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-amber-400 group-hover:bg-amber-400 group-hover:text-neutral-950 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 20h18L12 4 3 20z" />
                    <path d="M12 4v16" />
                    <path d="M9.5 20L12 13l2.5 7" />
                </svg>
            </span>
            <div>
                <span class="block text-lg font-bold tracking-tight text-white leading-none">Rawlseyyy</span>
                <span class="block text-[10px] uppercase tracking-[0.2em] text-amber-400 mt-1">Workspace {{ ucfirst($role) }}</span>
            </div>
        </a>

        <button
            type="button"
            @click="sidebarOpen = false"
            class="lg:hidden p-1.5 rounded-lg text-neutral-400 hover:text-white hover:bg-white/10 transition"
            aria-label="Tutup Sidebar"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Role-Based Vertical Menu List -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
        @if ($role === 'admin')
            <!-- Menu Utama -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-500">Menu Utama</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Master Data -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-500">Master Data</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.categories.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.categories.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Master Kategori</span>
                    </a>
                    <a href="{{ route('admin.items.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.items.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Master Alat</span>
                    </a>
                    <a href="{{ route('admin.articles.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.articles.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2" />
                        </svg>
                        <span>Master Artikel</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.users.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Manajemen Akun</span>
                    </a>
                </div>
            </div>

            <!-- Transaksi & Audit -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-500">Transaksi &amp; Audit</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.bookings.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.bookings.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Transaksi Sewa</span>
                    </a>
                    <a href="{{ route('admin.damage-reports.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.damage-reports.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Laporan Kerusakan</span>
                    </a>
                    <a href="{{ route('admin.activity-logs.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.activity-logs.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Log Aktivitas</span>
                    </a>
                </div>
            </div>
        @elseif ($role === 'staff')
            <!-- Menu Utama -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-500">Menu Utama</p>
                <div class="space-y-1">
                    <a href="{{ route('staff.dashboard') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('staff.dashboard') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Transaksi Sewa -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-500">Transaksi Sewa</p>
                <div class="space-y-1">
                    <a href="{{ route('staff.scan') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('staff.scan') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span>Scan QR Basecamp</span>
                    </a>
                    <a href="{{ route('staff.bookings.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('staff.bookings.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Manajemen Transaksi</span>
                    </a>
                    <a href="{{ route('staff.returns') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('staff.returns') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        <span>Status Pengembalian</span>
                    </a>
                    <a href="{{ route('staff.cancellations.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('staff.cancellations.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Approval Pembatalan</span>
                    </a>
                </div>
            </div>

            <!-- Operasional -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-500">Operasional</p>
                <div class="space-y-1">
                    <a href="{{ route('staff.items.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('staff.items.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Data Alat</span>
                    </a>
                    <a href="{{ route('staff.articles.index') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('staff.articles.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2" />
                        </svg>
                        <span>Data Artikel</span>
                    </a>
                </div>
            </div>
        @else
            <!-- Menu Member -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-500">Menu Member</p>
                <div class="space-y-1">
                    <a href="{{ route('user.dashboard') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('user.dashboard', 'dashboard') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard Utama</span>
                    </a>
                    <a href="{{ route('user.rentals') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('user.rentals') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Riwayat Sewa</span>
                    </a>
                    <a href="{{ route('katalog') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm text-neutral-300 hover:text-white hover:bg-white/10 font-medium transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Katalog Alat</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('profile.*') ? 'bg-amber-400 text-neutral-950 font-semibold shadow-sm' : 'text-neutral-300 hover:text-white hover:bg-white/10 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profil</span>
                    </a>
                </div>
            </div>
        @endif

        <!-- Pintasan Storefront -->
        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-500">Storefront</p>
            <div class="space-y-1">
                <a href="{{ url('/') }}" @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm text-neutral-300 hover:text-amber-400 hover:bg-white/10 font-medium transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Beranda Website</span>
                </a>
                <a href="{{ route('katalog') }}" @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm text-neutral-300 hover:text-amber-400 hover:bg-white/10 font-medium transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Katalog Publik</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-white/10 bg-neutral-900/50 shrink-0">
        <div class="flex items-center gap-3 mb-3 px-1">
            <div class="w-9 h-9 rounded-full bg-amber-400 text-neutral-950 font-bold text-sm flex items-center justify-center shrink-0">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-neutral-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-red-500/20 hover:text-red-300 border border-white/10 text-xs font-semibold text-neutral-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</aside>
