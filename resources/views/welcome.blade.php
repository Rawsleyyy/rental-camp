<x-public-layout title="Rawlseyyy — Persewaan Alat Camping & Outdoor">
    <!-- Hero Section -->
    <section class="relative bg-neutral-950 text-white overflow-hidden py-20 lg:py-28">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?auto=format&fit=crop&w=1920&q=80"
                alt="Suasana camping malam di alam terbuka Rawlseyyy"
                class="w-full h-full object-cover opacity-30"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-neutral-950 via-neutral-950/90 to-neutral-950/55"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-amber-400">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Persewaan Alat Camping &amp; Outdoor Terpercaya
                </p>
                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight text-white">
                    Lengkapi Petualangan Alammu Bersama Rawlseyyy
                </h1>
                <p class="text-neutral-300 text-base sm:text-lg max-w-xl leading-relaxed">
                    Jelajahi katalog perlengkapan camping dan paket sewa lengkap secara bebas. Pilih alat favorit, cek ketersediaan stok per tanggal, dan ambil pesanan dengan Kartu Pengambilan QR Code.
                </p>
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ route('katalog') }}" class="px-7 py-3.5 rounded-full bg-white text-neutral-950 text-sm font-semibold hover:bg-amber-400 transition shadow-lg">
                        Eksplor Katalog
                    </a>
                    <a href="{{ route('cek-booking') }}" class="px-6 py-3.5 rounded-full border border-white/25 text-white text-sm font-medium hover:bg-white/10 transition">
                        Cek Booking &amp; QR Code
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="rounded-3xl overflow-hidden border border-white/10 bg-neutral-900 shadow-2xl aspect-[4/3]">
                    <img
                        src="https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?auto=format&fit=crop&w=1000&q=80"
                        alt="Tenda camping berdiri di alam bebas"
                        class="w-full h-full object-cover"
                    >
                </div>
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
                            <div class="relative bg-neutral-100 rounded-2xl aspect-square overflow-hidden">
                                <span class="absolute top-3 left-3 z-10 px-3 py-1 rounded-full bg-white/95 text-neutral-900 text-[11px] font-semibold shadow-sm">
                                    Stok {{ $item->total_stock }}
                                </span>
                                <img
                                    src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?auto=format&fit=crop&w=800&q=80' }}"
                                    alt="{{ $item->name }}"
                                    class="w-full h-full object-cover"
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

                        <form action="{{ route('cart.store') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full py-2.5 px-4 rounded-full bg-neutral-950 text-white text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                                + Tambah ke Keranjang
                            </button>
                        </form>
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
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $package->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full py-2.5 px-4 rounded-full bg-white text-neutral-950 text-xs font-semibold hover:bg-amber-400 transition">
                                        Sewa Paket Ini
                                    </button>
                                </form>
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
