<x-public-layout title="Pack & Pitch — Persewaan Alat Camping & Outdoor">
    @php
        $isNonRenter = Auth::check() && in_array(Auth::user()->role, ['admin', 'staff'], true);
        $previewGear = ($featuredEquipments ?? collect())->firstWhere('image_path', '!=', null) ?? ($featuredEquipments ?? collect())->first();
    @endphp

    <!-- Hero Section (Daylight Atmospheric Panorama) -->
    <section class="relative overflow-hidden bg-gradient-to-br from-skyblock-100/70 via-white to-earth-100/40 pt-16 pb-24 sm:pt-20 sm:pb-28 lg:pt-24 lg:pb-32">
        <!-- Scenic Backdrop with Soft Daylight Scrim -->
        <div class="absolute inset-0">
            <img
                src="{{ asset('storage/hero/alpine-meadow.jpg') }}"
                alt="Panorama pegunungan dan padang rumput Pack &amp; Pitch"
                class="w-full h-full object-cover object-center"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-[#FDFCFA] via-[#FDFCFA]/85 sm:via-[#FDFCFA]/55 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-white/25 via-transparent to-canvas-warm/65"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl space-y-6">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-earth-600">
                    Persewaan Alat Camping &amp; Outdoor
                </p>

                <h1 class="font-display text-5xl sm:text-6xl font-bold leading-[1.06] tracking-tight">
                    <span class="block text-ink">Lengkapi</span>
                    <span class="block text-ink">Petualangan</span>
                    <span class="inline-flex items-center gap-2.5 text-meadow-500">
                        Alammu
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-meadow-500 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/>
                        </svg>
                    </span>
                </h1>

                <p class="text-ink-muted text-sm sm:text-base leading-relaxed">
                    Jelajahi katalog perlengkapan camping dan paket sewa lengkap secara bebas. Pilih alat favorit, cek ketersediaan stok per tanggal, dan ambil pesanan dengan Kartu Pengambilan QR Code.
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-1">
                    <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-meadow-500 hover:bg-meadow-600 text-white text-sm font-semibold transition shadow-md shadow-meadow-500/25">
                        <span>Eksplor Katalog</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="{{ route('cek-booking') }}" class="inline-flex items-center gap-2.5 group text-sm font-semibold text-ink hover:text-meadow-700 transition">
                        <span class="w-10 h-10 rounded-full bg-white border border-meadow-500/50 text-meadow-600 group-hover:bg-meadow-500 group-hover:text-white flex items-center justify-center shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </span>
                        <span>Cek Booking &amp; QR</span>
                    </a>
                </div>

                <!-- Minimal Trust Pills -->
                <div class="pt-3 flex flex-wrap items-center gap-2.5 text-xs font-medium text-ink">
                    <span class="inline-flex items-center gap-2 bg-white/85 backdrop-blur-sm border border-canvas-border rounded-full px-3.5 py-1.5 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-meadow-500"></span>
                        Gear Terawat &amp; Steril
                    </span>
                    <span class="inline-flex items-center gap-2 bg-white/85 backdrop-blur-sm border border-canvas-border rounded-full px-3.5 py-1.5 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-earth-500"></span>
                        Cek Stok Real-Time
                    </span>
                    <span class="inline-flex items-center gap-2 bg-white/85 backdrop-blur-sm border border-canvas-border rounded-full px-3.5 py-1.5 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-skyblock-500"></span>
                        Ambil Cepat via QR
                    </span>
                </div>
            </div>
        </div>

        <!-- Organic Wave Divider -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 leading-none">
            <svg viewBox="0 0 1440 72" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-10 sm:h-14" preserveAspectRatio="none">
                <path d="M0 32C240 68 480 68 720 40C960 12 1200 12 1440 44V72H0V32Z" fill="#FAF7F2"/>
            </svg>
        </div>
    </section>

    <!-- Tentang Pack & Pitch Split Section -->
    <section class="bg-canvas-warm py-14 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            <!-- Left: Overlapping Dual Framed Cards -->
            <div class="lg:col-span-6 relative pb-8 sm:pb-10">
                <div class="rounded-3xl border-4 border-white shadow-[0_18px_40px_-18px_rgba(43,43,43,0.22)] overflow-hidden aspect-[16/10] bg-skyblock-100">
                    <img
                        src="{{ asset('storage/hero/alpine-meadow.jpg') }}"
                        alt="Suasana camp pegunungan bersama Pack &amp; Pitch"
                        class="w-full h-full object-cover"
                    >
                </div>
                <div class="absolute bottom-0 right-3 sm:right-6 w-44 sm:w-52 rounded-2xl border-4 border-white shadow-[0_14px_30px_-12px_rgba(43,43,43,0.25)] bg-white p-3">
                    @if ($previewGear && $previewGear->image_path)
                        <div class="aspect-[4/3] rounded-xl bg-canvas-warm overflow-hidden flex items-center justify-center p-2">
                            <img
                                src="{{ asset('storage/' . $previewGear->image_path) }}"
                                alt="{{ $previewGear->name }}"
                                class="w-full h-full object-contain"
                            >
                        </div>
                        <p class="mt-2 text-[11px] font-semibold text-ink truncate">{{ $previewGear->name }}</p>
                        <p class="text-[10px] font-medium text-meadow-600">Gear Terawat &bull; Steril</p>
                    @else
                        <div class="aspect-[4/3] rounded-xl bg-meadow-50 flex flex-col items-center justify-center text-center p-3">
                            <x-brand-mark size="lg" />
                            <p class="mt-2 text-xs font-bold text-ink">Gear Terawat &bull; Steril</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Story & Link -->
            <div class="lg:col-span-6 relative">
                <!-- Subtle Pale Outdoor Compass Watermark -->
                <svg class="hidden sm:block pointer-events-none absolute -top-6 right-0 w-36 h-36 text-earth-200/70" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="32" cy="32" r="24" />
                    <polygon points="32,14 38,32 32,50 26,32" />
                    <line x1="8" y1="32" x2="16" y2="32" />
                    <line x1="48" y1="32" x2="56" y2="32" />
                </svg>

                <div class="relative space-y-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-earth-600">Tentang Pack &amp; Pitch</p>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold text-ink leading-tight">
                        Partner Andalan Setiap Pendakian
                    </h2>
                    <p class="text-sm sm:text-base text-ink-muted leading-relaxed">
                        Kami menyediakan perlengkapan camping dan pendakian yang bersih, terawat, dan siap pakai untuk perjalanan solo maupun rombongan. Cek ketersediaan tanggal secara real-time, pesan dari rumah, lalu ambil di basecamp dengan QR Code.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-ink border-b-2 border-meadow-500 pb-1 hover:text-meadow-600 transition">
                            <span>Jelajahi Katalog</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Equipments -->
    <section class="bg-white py-16 lg:py-20 border-t border-canvas-border/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-earth-600 mb-1.5">Alat Satuan Unggulan</p>
                    <h2 class="font-display text-3xl font-bold text-ink">Alat Camping Pilihan</h2>
                </div>
                <a href="{{ route('katalog', ['type' => 'satuan']) }}" class="text-sm font-semibold text-meadow-700 hover:text-meadow-600 transition">
                    Lihat Semua Alat &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($featuredEquipments as $item)
                    <div class="bg-white rounded-3xl p-4 border border-canvas-border shadow-[0_8px_24px_-12px_rgba(43,43,43,0.08)] hover:border-meadow-400 hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="relative {{ $item->image_path ? 'bg-canvas-warm border border-canvas-border/60' : 'bg-canvas-warm' }} rounded-2xl aspect-square overflow-hidden">
                                <span class="absolute top-3 left-3 z-10 px-3 py-1 rounded-full bg-white/95 text-meadow-700 text-[11px] font-semibold shadow-sm border border-meadow-200">
                                    Stok {{ $item->total_stock }}
                                </span>
                                <img
                                    src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?auto=format&fit=crop&w=800&q=80' }}"
                                    alt="{{ $item->name }}"
                                    class="{{ $item->image_path ? 'w-full h-full object-contain p-3' : 'w-full h-full object-cover' }}"
                                >
                            </div>
                            <div class="pt-4">
                                <p class="text-[11px] font-semibold uppercase tracking-widest text-earth-600">
                                    {{ $item->category->name ?? 'Camping Gear' }}
                                </p>
                                <h3 class="mt-1 text-base font-bold text-ink">{{ $item->name }}</h3>
                                <p class="mt-1 text-sm font-semibold text-ink">
                                    Rp {{ number_format($item->daily_rate, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-ink-muted">/ hari</span>
                                </p>
                            </div>
                        </div>

                        @if ($isNonRenter)
                            <button type="button" disabled class="mt-4 w-full py-2.5 px-4 rounded-full bg-canvas-warm text-ink-muted text-xs font-semibold border border-canvas-border cursor-not-allowed">
                                Khusus Penyewa (Role User)
                            </button>
                        @elseif ($item->total_stock > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full py-2.5 px-4 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                    + Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <button type="button" disabled class="mt-4 w-full py-2.5 px-4 rounded-full bg-canvas-warm text-ink-muted text-xs font-semibold border border-canvas-border cursor-not-allowed">
                                Stok Kosong
                            </button>
                        @endif
                    </div>
                @empty
                    <p class="col-span-4 text-center text-sm text-ink-muted py-8">Belum ada alat camping tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Rental Packages -->
    @if ($featuredPackages->isNotEmpty())
        <section class="bg-earth-100/40 py-16 border-t border-canvas-border">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-earth-600 mb-1.5">Paket Hemat</p>
                        <h2 class="font-display text-3xl font-bold text-ink">Paket Sewa Camping</h2>
                    </div>
                    <a href="{{ route('katalog', ['type' => 'paket']) }}" class="text-sm font-semibold text-meadow-700 hover:text-meadow-600 transition">
                        Lihat Semua Paket &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredPackages as $package)
                        <div class="bg-white rounded-3xl p-5 border border-earth-200/80 shadow-sm hover:border-meadow-400 hover:shadow-md transition flex flex-col justify-between relative overflow-hidden">
                            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-meadow-500 via-earth-500 to-skyblock-300"></div>
                            <div class="pt-1">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-3 py-1 rounded-full bg-meadow-100 text-meadow-700 text-[11px] font-bold">Paket Sewa</span>
                                    <span class="text-xs font-medium text-ink-muted">Stok {{ $package->total_stock }}</span>
                                </div>
                                <h3 class="text-base font-bold text-ink">{{ $package->name }}</h3>
                                <p class="mt-2 text-xs text-ink-muted leading-relaxed line-clamp-3">{{ $package->description }}</p>
                            </div>
                            <div class="pt-5 mt-4 border-t border-canvas-border/70">
                                <p class="text-base font-bold text-meadow-700 mb-3">
                                    Rp {{ number_format($package->daily_rate, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-ink-muted">/ hari</span>
                                </p>
                                @if ($isNonRenter)
                                    <button type="button" disabled class="w-full py-2.5 px-4 rounded-full bg-canvas-warm text-ink-muted text-xs font-semibold border border-canvas-border cursor-not-allowed">
                                        Khusus Penyewa (Role User)
                                    </button>
                                @elseif ($package->total_stock > 0)
                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $package->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full py-2.5 px-4 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                            Sewa Paket Ini
                                        </button>
                                    </form>
                                @else
                                    <button type="button" disabled class="w-full py-2.5 px-4 rounded-full bg-canvas-warm text-ink-muted text-xs font-semibold border border-canvas-border cursor-not-allowed">
                                        Stok Kosong
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Step-by-step Reservation Guide -->
    <section class="bg-white py-16 lg:py-20 border-t border-canvas-border/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-earth-600 mb-2">Cara Reservasi</p>
                <h2 class="font-display text-3xl font-bold text-ink">4 Langkah Mudah Sewa Alat di Pack &amp; Pitch</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="rounded-3xl border border-canvas-border p-6 bg-canvas-warm/70 shadow-sm">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-meadow-500 text-white font-display text-lg font-bold shadow-sm">1</span>
                    <h3 class="mt-4 text-base font-bold text-ink">Pilih Alat / Paket</h3>
                    <p class="mt-2 text-xs text-ink-muted leading-relaxed">Telusuri katalog alat camping atau paket sewa, lalu tambahkan ke keranjang sesuai kebutuhan.</p>
                </div>
                <div class="rounded-3xl border border-canvas-border p-6 bg-canvas-warm/70 shadow-sm">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-earth-500 text-white font-display text-lg font-bold shadow-sm">2</span>
                    <h3 class="mt-4 text-base font-bold text-ink">Tentukan Tanggal</h3>
                    <p class="mt-2 text-xs text-ink-muted leading-relaxed">Pilih tanggal mulai dan selesai di halaman checkout. Sistem otomatis mengecek stok tersedia.</p>
                </div>
                <div class="rounded-3xl border border-canvas-border p-6 bg-canvas-warm/70 shadow-sm">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-skyblock-500 text-white font-display text-lg font-bold shadow-sm">3</span>
                    <h3 class="mt-4 text-base font-bold text-ink">ACC Staff &amp; Bayar</h3>
                    <p class="mt-2 text-xs text-ink-muted leading-relaxed">Setelah Staff menyetujui booking, selesaikan pembayaran lewat Transfer Bank atau QRIS di halaman Profil.</p>
                </div>
                <div class="rounded-3xl border border-canvas-border p-6 bg-canvas-warm/70 shadow-sm">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-meadow-600 text-white font-display text-lg font-bold shadow-sm">4</span>
                    <h3 class="mt-4 text-base font-bold text-ink">Ambil dengan QR Code</h3>
                    <p class="mt-2 text-xs text-ink-muted leading-relaxed">Tunjukkan Kartu Pengambilan &amp; QR Code dari halaman Cek Booking saat mengambil alat di basecamp.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Inspirational Quote Band -->
    <section class="relative overflow-hidden bg-gradient-to-r from-canvas-warm via-white to-skyblock-100/60 border-t border-canvas-border py-14">
        <img
            src="{{ asset('storage/hero/alpine-meadow.jpg') }}"
            alt=""
            aria-hidden="true"
            class="pointer-events-none absolute right-0 inset-y-0 w-full sm:w-2/3 h-full object-cover opacity-20"
        >
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-canvas-warm via-canvas-warm/90 to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="max-w-lg flex items-start gap-3">
                <span class="font-display text-5xl leading-none text-meadow-500 select-none">&ldquo;</span>
                <p class="font-display text-2xl sm:text-3xl font-bold text-ink leading-snug pt-1">
                    Langkah kecil menuju puncak besar. &mdash;
                </p>
            </div>
            <a href="{{ route('katalog') }}" class="inline-flex self-start sm:self-auto items-center gap-2 px-6 py-2.5 rounded-full bg-white border border-meadow-500/60 text-meadow-700 text-xs font-semibold hover:bg-meadow-50 transition shadow-sm">
                Mulai Petualangan &rarr;
            </a>
        </div>
    </section>

    <!-- Latest Articles -->
    <section class="bg-canvas-warm py-16 lg:py-20 border-t border-canvas-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-earth-600 mb-1.5">Jurnal Pendaki</p>
                    <h2 class="font-display text-3xl font-bold text-ink">Artikel &amp; Panduan Terbaru</h2>
                </div>
                <a href="{{ route('artikel.index') }}" class="text-sm font-semibold text-meadow-700 hover:text-meadow-600 transition">
                    Semua Artikel &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($latestArticles as $article)
                    <article class="bg-white rounded-3xl overflow-hidden border border-canvas-border shadow-sm hover:border-meadow-400 hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="aspect-[16/10] bg-canvas-warm overflow-hidden">
                                <img
                                    src="{{ $article->image_path ? asset('storage/' . $article->image_path) : 'https://images.unsplash.com/photo-1476041800959-2f6bb412c8ce?auto=format&fit=crop&w=800&q=80' }}"
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>
                            <div class="p-6">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-earth-600">
                                    {{ $article->created_at?->format('d M Y') }}
                                </p>
                                <h3 class="mt-2 text-lg font-bold text-ink">{{ $article->title }}</h3>
                                <p class="mt-2 text-xs text-ink-muted leading-relaxed line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 120) }}
                                </p>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <a href="{{ route('artikel.show', $article->slug) }}" class="text-xs font-semibold text-meadow-700 hover:text-meadow-600 transition">
                                Baca Artikel &rarr;
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="col-span-3 text-center text-sm text-ink-muted py-8">Belum ada artikel diterbitkan.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-public-layout>
