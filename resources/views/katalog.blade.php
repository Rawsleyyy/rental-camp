<x-public-layout title="Katalog Alat & Paket Sewa — Pack & Pitch">
    @php
        $isNonRenter = Auth::check() && in_array(Auth::user()->role, ['admin', 'staff'], true);
    @endphp
    <!-- Header Banner -->
    <section class="relative overflow-hidden bg-gradient-to-br from-skyblock-100/80 via-white to-earth-100/65 border-b border-canvas-border text-ink py-14">
        <div class="absolute top-0 inset-x-0 h-1 palette-strip"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="inline-flex px-3.5 py-1 rounded-full bg-meadow-100 text-meadow-700 text-xs font-semibold uppercase tracking-[0.22em] mb-3">Katalog Lengkap</p>
            <h1 class="font-display text-3xl sm:text-4xl font-bold text-ink">Alat Camping &amp; Paket Sewa Pack &amp; Pitch</h1>
            <p class="text-ink-muted text-sm max-w-2xl mx-auto mt-3">
                Temukan perlengkapan satuan maupun paket hemat untuk pendakian dan berkemah. Tambahkan ke keranjang lalu pilih tanggal sewa pada saat checkout.
            </p>
        </div>
    </section>

    <!-- Search & Filter Bar -->
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('katalog') }}" class="bg-white rounded-3xl border border-canvas-border p-5 shadow-sm mb-8 grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-5">
                    <label for="q" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Cari Alat / Paket</label>
                    <input
                        type="text"
                        id="q"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari tenda, carrier, kompor, paket..."
                        class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
                    >
                </div>

                <div class="md:col-span-3">
                    <label for="category" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Kategori</label>
                    <select id="category" name="category" class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}" @selected($categorySlug === $cat->slug)>
                                {{ $cat->name }} ({{ $cat->items_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Tipe</label>
                    <select id="type" name="type" class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">
                        <option value="all" @selected($typeFilter === 'all')>Semua</option>
                        <option value="satuan" @selected($typeFilter === 'satuan')>Alat Satuan</option>
                        <option value="paket" @selected($typeFilter === 'paket')>Paket Sewa</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                        Filter
                    </button>
                    @if ($search !== '' || $categorySlug !== '' || $typeFilter !== 'all')
                        <a href="{{ route('katalog') }}" class="py-2.5 px-3 rounded-full border border-canvas-border text-xs font-semibold text-ink-muted hover:bg-canvas-warm transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- Items Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($items as $item)
                    <div class="bg-white rounded-3xl p-4 border border-canvas-border shadow-[0_8px_24px_-12px_rgba(43,43,43,0.08)] hover:border-meadow-400 hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="relative {{ $item->image_path ? 'bg-white border border-canvas-border/70' : 'bg-canvas-warm' }} rounded-2xl aspect-square overflow-hidden">
                                <span class="absolute top-3 left-3 z-10 px-3 py-1 rounded-full text-[11px] font-semibold shadow-sm border {{ $item->is_package ? 'bg-earth-500 text-white border-earth-600' : 'bg-white/95 text-meadow-700 border-meadow-200' }}">
                                    {{ $item->is_package ? 'Paket Sewa' : 'Satuan' }}
                                </span>
                                <span class="absolute top-3 right-3 z-10 px-2.5 py-1 rounded-full bg-meadow-500 text-white text-[11px] font-semibold shadow-sm">
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
                                <h2 class="mt-1 text-base font-bold text-ink">{{ $item->name }}</h2>
                                <p class="mt-1.5 text-xs text-ink-muted leading-relaxed line-clamp-2">{{ $item->description }}</p>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-canvas-border/70">
                            <p class="text-base font-bold text-ink mb-3">
                                Rp {{ number_format($item->daily_rate, 0, ',', '.') }}
                                <span class="text-xs font-normal text-ink-muted">/ hari</span>
                            </p>

                            @if ($isNonRenter)
                                <button type="button" disabled class="w-full py-2.5 px-4 rounded-full bg-canvas-warm text-ink-muted text-xs font-semibold border border-canvas-border cursor-not-allowed">
                                    Khusus Penyewa (Role User)
                                </button>
                            @elseif ($item->total_stock > 0)
                                <form action="{{ route('cart.store') }}" method="POST" class="space-y-2">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="number"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            max="{{ $item->total_stock }}"
                                            class="w-16 rounded-xl border-canvas-border text-xs text-center py-2 focus:border-meadow-500 focus:ring-meadow-500"
                                            aria-label="Jumlah unit"
                                        >
                                        <button type="submit" class="flex-1 py-2 px-3 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                            + Keranjang
                                        </button>
                                    </div>
                                    <button type="submit" name="buy_now" value="1" class="w-full py-2 px-3 rounded-full border border-meadow-500/60 text-meadow-700 text-xs font-semibold hover:bg-meadow-50 transition">
                                        Sewa Sekarang
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled class="w-full py-2.5 px-4 rounded-full bg-canvas-warm text-ink-muted text-xs font-semibold border border-canvas-border cursor-not-allowed">
                                    Stok Kosong
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-3xl border border-canvas-border p-12 text-center">
                        <p class="text-sm font-semibold text-ink">Tidak ada alat yang sesuai dengan pencarian Anda.</p>
                        <a href="{{ route('katalog') }}" class="mt-3 inline-block text-xs font-semibold text-meadow-700 hover:underline">
                            Tampilkan semua katalog
                        </a>
                    </div>
                @endforelse
            </div>

            @if ($items->hasPages())
                <div class="mt-10">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
