<x-public-layout title="Rawlseyyy — Persewaan Alat Camping & Outdoor">
    @php
        $isNonRenter = Auth::check() && in_array(Auth::user()->role, ['admin', 'staff'], true);
    @endphp
    <!-- Hero Section -->
    <section class="relative bg-neutral-950 text-white overflow-hidden py-16 sm:py-20 lg:py-24">
        <!-- Warmer, Balanced Mountain Sunrise Backdrop -->
        <div class="absolute inset-0">
            <img
                src="{{ asset('storage/hero/prau-sunrise.jpg') }}"
                alt="Panorama sunrise pegunungan dan tenda camping Rawlseyyy"
                class="w-full h-full object-cover object-center opacity-70"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-neutral-950/90 via-neutral-950/55 to-neutral-950/20"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-transparent to-neutral-950/35"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            <!-- Left Column -->
            <div class="lg:col-span-7 space-y-6">
                <p class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400/15 border border-amber-400/30 backdrop-blur-sm text-[11px] font-semibold tracking-widest uppercase text-amber-300">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Persewaan Alat Camping &amp; Outdoor Terpercaya
                </p>

                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.1] text-white drop-shadow-md">
                    Lengkapi Petualangan Alammu Bersama Rawlseyyy
                </h1>

                <p class="text-neutral-200/95 text-base sm:text-lg max-w-xl leading-relaxed">
                    Jelajahi katalog perlengkapan camping dan paket sewa lengkap secara bebas. Pilih alat favorit, cek ketersediaan stok per tanggal, dan ambil pesanan dengan Kartu Pengambilan QR Code.
                </p>

                <div class="flex flex-wrap items-center gap-3.5 pt-1">
                    <a href="{{ route('katalog') }}" class="px-7 py-3.5 rounded-full bg-white text-neutral-950 text-sm font-semibold hover:bg-amber-400 transition shadow-lg">
                        Eksplor Katalog
                    </a>
                    <a href="{{ route('cek-booking') }}" class="px-6 py-3.5 rounded-full border border-white/25 bg-neutral-950/45 backdrop-blur-md text-white text-sm font-medium hover:bg-white/15 transition">
                        Cek Booking &amp; QR Code
                    </a>
                </div>

                <!-- Minimal Trust Bar -->
                <div class="pt-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-xs font-medium text-neutral-300">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        Gear Terawat &amp; Steril
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        Cek Stok Real-Time
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        Ambil Cepat via QR
                    </span>
                </div>
            </div>

            <!-- Right Column: Glassmorphic Product Showcase -->
            <div class="lg:col-span-5 relative">
                <!-- Warm Ambient Sunrise Glow -->
                <div class="hidden sm:block absolute -inset-4 bg-amber-400/20 blur-3xl rounded-full pointer-events-none"></div>

                @php
                    $showcaseData = ($heroShowcaseItems ?? collect())->map(fn ($item) => [
                        'name' => $item->name,
                        'category' => $item->category->name ?? 'Camping Gear',
                        'price' => 'Rp ' . number_format($item->daily_rate, 0, ',', '.'),
                        'stock' => $item->total_stock,
                        'image' => asset('storage/' . $item->image_path),
                    ])->values()->all();
                @endphp

                @if (count($showcaseData) > 0)
                    <div
                        x-data="{
                            items: @js($showcaseData),
                            active: 0,
                            timer: null,
                            start() {
                                if (this.items.length <= 1) return;
                                this.stop();
                                this.timer = setInterval(() => this.next(), 3500);
                            },
                            stop() {
                                if (this.timer) {
                                    clearInterval(this.timer);
                                    this.timer = null;
                                }
                            },
                            next() {
                                this.active = (this.active + 1) % this.items.length;
                            },
                            prev() {
                                this.active = (this.active - 1 + this.items.length) % this.items.length;
                            }
                        }"
                        x-init="start()"
                        @mouseenter="stop()"
                        @mouseleave="start()"
                        class="relative rounded-[2rem] bg-white/10 backdrop-blur-xl border border-white/20 p-3 shadow-2xl space-y-3"
                    >
                        <!-- Warm Studio Stage -->
                        <div class="relative rounded-[1.4rem] bg-gradient-to-br from-stone-100 via-amber-50/70 to-stone-200/90 aspect-[4/3] overflow-hidden">
                            <span
                                class="absolute top-3 left-3 z-10 px-3 py-1 rounded-full bg-neutral-950/75 backdrop-blur-md border border-white/15 text-amber-300 text-[11px] font-semibold shadow-sm"
                                x-text="items[active].category"
                            ></span>

                            <div class="absolute top-3 right-3 z-10 flex items-center gap-1.5">
                                <span
                                    class="px-2.5 py-1 rounded-full bg-white/75 backdrop-blur-md text-neutral-700 text-[11px] font-mono font-semibold border border-stone-300/60"
                                    x-text="String(active + 1).padStart(2, '0') + ' / ' + String(items.length).padStart(2, '0')"
                                ></span>
                                <span
                                    class="px-2.5 py-1 rounded-full bg-white/80 backdrop-blur-md text-neutral-900 text-[11px] font-semibold border border-stone-300/60"
                                    x-text="'Stok ' + items[active].stock"
                                ></span>
                            </div>

                            <img
                                :src="items[active].image"
                                :alt="items[active].name"
                                class="w-full h-full object-contain p-6 mix-blend-multiply hover:scale-105 transition duration-500"
                            >
                        </div>

                        <!-- Translucent Info Footer & Pill-Dot Controls -->
                        <div class="px-2 pb-1 space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm sm:text-base font-bold text-white truncate" x-text="items[active].name"></p>
                                    <p class="text-xs font-semibold text-amber-300 mt-0.5">
                                        <span x-text="items[active].price"></span>
                                        <span class="font-normal text-neutral-300">/ hari</span>
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <template x-if="items.length > 1">
                                        <div class="flex items-center gap-1.5">
                                            <button
                                                type="button"
                                                @click="prev()"
                                                aria-label="Produk sebelumnya"
                                                class="w-8 h-8 rounded-full bg-white/10 border border-white/15 text-white hover:bg-amber-400 hover:text-neutral-950 hover:border-amber-400 flex items-center justify-center transition"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <button
                                                type="button"
                                                @click="next()"
                                                aria-label="Produk berikutnya"
                                                class="w-8 h-8 rounded-full bg-white/10 border border-white/15 text-white hover:bg-amber-400 hover:text-neutral-950 hover:border-amber-400 flex items-center justify-center transition"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>

                                    <a href="{{ route('katalog', ['type' => 'satuan']) }}" class="px-3.5 py-2 rounded-full bg-white text-neutral-950 text-[11px] font-semibold hover:bg-amber-400 transition shadow-sm">
                                        Lihat Katalog &rarr;
                                    </a>
                                </div>
                            </div>

                            <!-- Expanding Pill-Dot Indicators -->
                            <template x-if="items.length > 1">
                                <div class="flex items-center justify-center gap-1.5 pt-0.5">
                                    <template x-for="(item, idx) in items" :key="idx">
                                        <button
                                            type="button"
                                            @click="active = idx"
                                            :aria-label="'Tampilkan ' + item.name"
                                            :class="active === idx ? 'w-6 bg-amber-400' : 'w-1.5 bg-white/35 hover:bg-white/60'"
                                            class="h-1.5 rounded-full transition-all duration-300"
                                        ></button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                @else
                    <div class="relative rounded-[2rem] bg-white/10 backdrop-blur-xl border border-white/20 p-3 shadow-2xl">
                        <div class="rounded-[1.4rem] overflow-hidden bg-neutral-900 aspect-[4/3]">
                            <img
                                src="{{ asset('storage/hero/prau-sunrise.jpg') }}"
                                alt="Panorama pegunungan dan tenda camping Rawlseyyy"
                                class="w-full h-full object-cover"
                            >
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Equipments -->
    <section class="bg-white py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 mb-1.5">Alat Satuan Unggulan</p>
                    <h2 class="font-serif text-3xl font-bold text-neutral-950">Alat Camping Pilihan</h2>
                </div>
                <a href="{{ route('katalog', ['type' => 'satuan']) }}" class="text-sm font-semibold text-neutral-950 hover:text-amber-600 transition">
                    Lihat Semua Alat &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($featuredEquipments as $item)
                    <div class="bg-white rounded-3xl p-4 border border-neutral-200/80 shadow-sm hover:shadow-lg transition flex flex-col justify-between">
                        <div>
                            <div class="relative {{ $item->image_path ? 'bg-white border border-neutral-100' : 'bg-neutral-100' }} rounded-2xl aspect-square overflow-hidden">
                                <span class="absolute top-3 left-3 z-10 px-3 py-1 rounded-full bg-white/95 text-neutral-900 text-[11px] font-semibold shadow-sm border border-neutral-200/70">
                                    Stok {{ $item->total_stock }}
                                </span>
                                <img
                                    src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?auto=format&fit=crop&w=800&q=80' }}"
                                    alt="{{ $item->name }}"
                                    class="{{ $item->image_path ? 'w-full h-full object-contain p-3' : 'w-full h-full object-cover' }}"
                                >
                            </div>
                            <div class="pt-4">
                                <p class="text-[11px] font-semibold uppercase tracking-widest text-amber-600">
                                    {{ $item->category->name ?? 'Camping Gear' }}
                                </p>
                                <h3 class="mt-1 text-base font-bold text-neutral-950">{{ $item->name }}</h3>
                                <p class="mt-1 text-sm font-semibold text-neutral-700">
                                    Rp {{ number_format($item->daily_rate, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-neutral-400">/ hari</span>
                                </p>
                            </div>
                        </div>

                        @if ($isNonRenter)
                            <button type="button" disabled class="mt-4 w-full py-2.5 px-4 rounded-full bg-neutral-100 text-neutral-500 text-xs font-semibold cursor-not-allowed">
                                Khusus Penyewa (Role User)
                            </button>
                        @elseif ($item->total_stock > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full py-2.5 px-4 rounded-full bg-neutral-950 text-white text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                                    + Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <button type="button" disabled class="mt-4 w-full py-2.5 px-4 rounded-full bg-neutral-200 text-neutral-500 text-xs font-semibold cursor-not-allowed">
                                Stok Kosong
                            </button>
                        @endif
                    </div>
                @empty
                    <p class="col-span-4 text-center text-sm text-neutral-500 py-8">Belum ada alat camping tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Rental Packages -->
    @if ($featuredPackages->isNotEmpty())
        <section class="bg-neutral-50 py-16 border-t border-neutral-200/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 mb-1.5">Paket Hemat</p>
                        <h2 class="font-serif text-3xl font-bold text-neutral-950">Paket Sewa Camping</h2>
                    </div>
                    <a href="{{ route('katalog', ['type' => 'paket']) }}" class="text-sm font-semibold text-neutral-950 hover:text-amber-600 transition">
                        Lihat Semua Paket &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredPackages as $package)
                        <div class="bg-neutral-950 text-white rounded-3xl p-5 shadow-md flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-3 py-1 rounded-full bg-amber-400 text-neutral-950 text-[11px] font-bold">Paket Sewa</span>
                                    <span class="text-xs text-neutral-400">Stok {{ $package->total_stock }}</span>
                                </div>
                                <h3 class="text-base font-bold text-white">{{ $package->name }}</h3>
                                <p class="mt-2 text-xs text-neutral-400 leading-relaxed line-clamp-3">{{ $package->description }}</p>
                            </div>
                            <div class="pt-5">
                                <p class="text-base font-bold text-amber-400 mb-3">
                                    Rp {{ number_format($package->daily_rate, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-neutral-400">/ hari</span>
                                </p>
                                @if ($isNonRenter)
                                    <button type="button" disabled class="w-full py-2.5 px-4 rounded-full bg-neutral-800 text-neutral-400 text-xs font-semibold cursor-not-allowed">
                                        Khusus Penyewa (Role User)
                                    </button>
                                @elseif ($package->total_stock > 0)
                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $package->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full py-2.5 px-4 rounded-full bg-white text-neutral-950 text-xs font-semibold hover:bg-amber-400 transition">
                                            Sewa Paket Ini
                                        </button>
                                    </form>
                                @else
                                    <button type="button" disabled class="w-full py-2.5 px-4 rounded-full bg-neutral-800 text-neutral-500 text-xs font-semibold cursor-not-allowed">
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
    <section class="bg-white py-16 lg:py-20 border-t border-neutral-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 mb-2">Cara Reservasi</p>
                <h2 class="font-serif text-3xl font-bold text-neutral-950">4 Langkah Mudah Sewa Alat di Rawlseyyy</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="rounded-3xl border border-neutral-200 p-6 bg-white shadow-sm">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-neutral-950 text-amber-400 text-sm font-bold">1</span>
                    <h3 class="mt-4 text-base font-bold text-neutral-950">Pilih Alat / Paket</h3>
                    <p class="mt-2 text-xs text-neutral-500 leading-relaxed">Telusuri katalog alat camping atau paket sewa, lalu tambahkan ke keranjang sesuai kebutuhan.</p>
                </div>
                <div class="rounded-3xl border border-neutral-200 p-6 bg-white shadow-sm">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-neutral-950 text-amber-400 text-sm font-bold">2</span>
                    <h3 class="mt-4 text-base font-bold text-neutral-950">Tentukan Tanggal</h3>
                    <p class="mt-2 text-xs text-neutral-500 leading-relaxed">Pilih tanggal mulai dan selesai di halaman checkout. Sistem otomatis mengecek stok tersedia.</p>
                </div>
                <div class="rounded-3xl border border-neutral-200 p-6 bg-white shadow-sm">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-neutral-950 text-amber-400 text-sm font-bold">3</span>
                    <h3 class="mt-4 text-base font-bold text-neutral-950">ACC Staff &amp; Bayar</h3>
                    <p class="mt-2 text-xs text-neutral-500 leading-relaxed">Setelah Staff menyetujui booking, selesaikan pembayaran lewat Transfer Bank atau QRIS di halaman Profil.</p>
                </div>
                <div class="rounded-3xl border border-neutral-200 p-6 bg-white shadow-sm">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-neutral-950 text-amber-400 text-sm font-bold">4</span>
                    <h3 class="mt-4 text-base font-bold text-neutral-950">Ambil dengan QR Code</h3>
                    <p class="mt-2 text-xs text-neutral-500 leading-relaxed">Tunjukkan Kartu Pengambilan &amp; QR Code dari halaman Cek Booking saat mengambil alat di basecamp.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Articles -->
    <section class="bg-neutral-50 py-16 lg:py-20 border-t border-neutral-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 mb-1.5">Jurnal Pendaki</p>
                    <h2 class="font-serif text-3xl font-bold text-neutral-950">Artikel &amp; Panduan Terbaru</h2>
                </div>
                <a href="{{ route('artikel.index') }}" class="text-sm font-semibold text-neutral-950 hover:text-amber-600 transition">
                    Semua Artikel &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($latestArticles as $article)
                    <article class="bg-white rounded-3xl overflow-hidden border border-neutral-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="aspect-[16/10] bg-neutral-100 overflow-hidden">
                                <img
                                    src="{{ $article->image_path ? asset('storage/' . $article->image_path) : 'https://images.unsplash.com/photo-1476041800959-2f6bb412c8ce?auto=format&fit=crop&w=800&q=80' }}"
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>
                            <div class="p-6">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400">
                                    {{ $article->created_at?->format('d M Y') }}
                                </p>
                                <h3 class="mt-2 text-lg font-bold text-neutral-950">{{ $article->title }}</h3>
                                <p class="mt-2 text-xs text-neutral-500 leading-relaxed line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 120) }}
                                </p>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <a href="{{ route('artikel.show', $article->slug) }}" class="text-xs font-semibold text-neutral-950 hover:text-amber-600 transition">
                                Baca Artikel &rarr;
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="col-span-3 text-center text-sm text-neutral-500 py-8">Belum ada artikel diterbitkan.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-public-layout>
