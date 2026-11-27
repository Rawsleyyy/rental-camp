<x-public-layout title="Keranjang Sewa — Rawlseyyy">
    <section class="relative overflow-hidden bg-gradient-to-br from-skyblock-100/80 via-white to-earth-100/65 border-b border-canvas-border text-ink py-12">
        <div class="absolute top-0 inset-x-0 h-1 palette-strip"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="inline-flex px-3.5 py-1 rounded-full bg-meadow-100 text-meadow-700 text-xs font-semibold uppercase tracking-[0.22em] mb-3">Keranjang Saya</p>
            <h1 class="font-display text-3xl sm:text-4xl font-bold text-ink">Keranjang Sewa Alat Camping</h1>
            <p class="text-ink-muted text-sm mt-2">
                Atur jumlah unit alat sebelum melanjutkan ke halaman pemilihan tanggal &amp; checkout.
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($carts->isEmpty())
                <div class="bg-white rounded-3xl border border-canvas-border shadow-sm p-12 text-center">
                    <h2 class="font-display text-2xl font-bold text-ink">Keranjang Sewa Masih Kosong</h2>
                    <p class="text-sm text-ink-muted mt-2">Pilih alat camping atau paket sewa di katalog untuk memulai pemesanan.</p>
                    <a href="{{ route('katalog') }}" class="mt-6 inline-flex px-7 py-3 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                        Jelajahi Katalog Alat &rarr;
                    </a>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden">
                    <div class="divide-y divide-canvas-border/60">
                        @foreach ($carts as $cart)
                            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl bg-canvas-warm overflow-hidden shrink-0 border border-canvas-border">
                                        <img
                                            src="{{ $cart->item?->image_path ? asset('storage/' . $cart->item->image_path) : 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?auto=format&fit=crop&w=400&q=80' }}"
                                            alt="{{ $cart->item?->name }}"
                                            class="w-full h-full object-cover"
                                        >
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-widest text-earth-600">
                                            {{ $cart->item?->category?->name ?? 'Camping Gear' }}
                                        </p>
                                        <h2 class="text-base font-bold text-ink">{{ $cart->item?->name }}</h2>
                                        <p class="text-xs text-ink-muted mt-0.5">
                                            Rp {{ number_format($cart->item?->daily_rate ?? 0, 0, ',', '.') }} / hari &middot; Stok total: {{ $cart->item?->total_stock ?? 0 }} unit
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    <form action="{{ route('cart.update', $cart) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input
                                            type="number"
                                            name="quantity"
                                            value="{{ $cart->quantity }}"
                                            min="1"
                                            max="{{ max(1, $cart->item?->total_stock ?? 1) }}"
                                            class="w-20 rounded-xl border-canvas-border text-sm text-center py-2 focus:border-meadow-500 focus:ring-meadow-500"
                                        >
                                        <button type="submit" class="px-4 py-2 rounded-full border border-canvas-border text-xs font-semibold text-ink hover:bg-meadow-50 hover:border-meadow-400 hover:text-meadow-700 transition">
                                            Update
                                        </button>
                                    </form>

                                    <p class="text-sm font-bold text-ink min-w-[115px] text-right">
                                        Rp {{ number_format(($cart->item?->daily_rate ?? 0) * $cart->quantity, 0, ',', '.') }} <span class="text-xs font-normal text-ink-muted">/hr</span>
                                    </p>

                                    <form action="{{ route('cart.destroy', $cart) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3.5 py-2 rounded-full border border-red-200 text-xs font-semibold text-red-600 hover:bg-red-600 hover:text-white transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-canvas-warm px-6 py-5 border-t border-canvas-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-xs text-earth-600 uppercase tracking-wider font-semibold">Estimasi Tarif Harian</p>
                            <p class="font-display text-2xl font-bold text-ink">
                                Rp {{ number_format($dailySubtotal, 0, ',', '.') }} <span class="text-sm font-sans font-normal text-ink-muted">/ hari</span>
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('katalog') }}" class="px-5 py-2.5 rounded-full border border-meadow-500/60 bg-white text-xs font-semibold text-meadow-700 hover:bg-meadow-50 transition">
                                + Tambah Alat Lain
                            </a>
                            <a href="{{ route('checkout.index') }}" class="px-7 py-3 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                Lanjut ke Checkout &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
