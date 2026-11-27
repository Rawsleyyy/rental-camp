<x-app-layout>
    <!-- Welcome Header -->
    <x-admin-hero
        eyebrow="Rawlseyyy Admin Panel"
        :title="'Selamat Datang di Workspace Admin, ' . Auth::user()->name . '!'"
        subtitle="Pantau ringkasan ketersediaan alat camping, transaksi sewa, manajemen akun, dan operasional harian Rawlseyyy."
        actionLabel="+ Tambah Alat Baru"
        :actionHref="route('admin.items.create')"
    />

    <!-- Statistic Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <a href="{{ route('admin.items.index') }}" data-aos="fade-up" data-aos-delay="50" class="group bg-white rounded-3xl border border-canvas-border p-6 shadow-sm hover:shadow-md hover:border-meadow-400 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-ink-muted font-semibold">Total Alat Tersedia</span>
                <span class="w-9 h-9 rounded-2xl bg-meadow-100 flex items-center justify-center text-meadow-700 group-hover:bg-meadow-500 group-hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-ink">{{ $totalAvailableTools }}</p>
                    <span class="text-xs font-medium text-ink-muted">Unit</span>
                </div>
                <p class="mt-2 text-xs text-meadow-700 font-medium">Lihat Master Alat &rarr;</p>
            </div>
        </a>

        <a href="{{ route('admin.bookings.index') }}" data-aos="fade-up" data-aos-delay="100" class="group bg-white rounded-3xl border border-canvas-border p-6 shadow-sm hover:shadow-md hover:border-earth-400 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-ink-muted font-semibold">Menunggu Persetujuan</span>
                <span class="w-9 h-9 rounded-2xl bg-earth-100 flex items-center justify-center text-earth-700 group-hover:bg-earth-500 group-hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-ink">{{ $pendingApprovals }}</p>
                    <span class="text-xs font-medium text-ink-muted">Booking</span>
                </div>
                <p class="mt-2 text-xs text-earth-700 font-medium">Buka Transaksi Sewa &rarr;</p>
            </div>
        </a>

        <a href="{{ route('admin.bookings.index') }}" data-aos="fade-up" data-aos-delay="150" class="group bg-white rounded-3xl border border-canvas-border p-6 shadow-sm hover:shadow-md hover:border-skyblock-400 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-ink-muted font-semibold">Penyewaan Aktif</span>
                <span class="w-9 h-9 rounded-2xl bg-skyblock-100 flex items-center justify-center text-skyblock-700 group-hover:bg-skyblock-500 group-hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-ink">{{ $activeRentals }}</p>
                    <span class="text-xs font-medium text-ink-muted">Transaksi</span>
                </div>
                <p class="mt-2 text-xs text-skyblock-700 font-medium">Pantau Transaksi &rarr;</p>
            </div>
        </a>

        <a href="{{ route('admin.damage-reports.index') }}" data-aos="fade-up" data-aos-delay="200" class="group bg-gradient-to-br from-earth-500 to-earth-600 text-white rounded-3xl border border-earth-600 p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-earth-100 font-semibold">Laporan Kerusakan</span>
                <span class="w-9 h-9 rounded-2xl bg-white/15 flex items-center justify-center text-white group-hover:bg-white group-hover:text-earth-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-white">{{ $damageReports }}</p>
                    <span class="text-xs font-medium text-earth-100">Unit</span>
                </div>
                <p class="mt-2 text-xs text-earth-50 font-semibold">Buka Halaman Kerusakan &rarr;</p>
            </div>
        </a>
    </div>

    <!-- Single Full-Width Recent Equipment Table -->
    <div data-aos="fade-up" data-aos-delay="150" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">Inventaris Alat Camping Terbaru</h2>
                <p class="text-xs text-ink-muted mt-0.5">
                    {{ $categoryCount }} kategori &middot; {{ $itemCount }} alat &middot; {{ $articleCount }} artikel &middot; {{ $userCount }} akun terdaftar
                </p>
            </div>
            <a href="{{ route('admin.items.index') }}" class="inline-flex items-center justify-center self-start sm:self-auto rounded-full bg-meadow-500 text-white px-5 py-2 text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                Lihat Semua Master Alat
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Nama Alat</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kategori</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Stok</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Tarif / Hari</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($recentItems as $item)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-canvas-warm overflow-hidden shrink-0 flex items-center justify-center border border-canvas-border">
                                        @if ($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[10px] uppercase tracking-wider text-ink-muted">Gear</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-ink">{{ $item->name }}</p>
                                        <p class="text-xs font-mono text-ink-muted">{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted">
                                {{ $item->category->name ?? 'Tanpa Kategori' }}
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $item->total_stock <= 3 ? 'bg-earth-100 text-earth-700' : 'bg-meadow-100 text-meadow-700' }}">
                                    {{ $item->total_stock }} Unit
                                </span>
                            </td>
                            <td class="px-7 py-4 text-right text-sm font-semibold text-ink">
                                Rp {{ number_format($item->daily_rate, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-7 py-10 text-center text-sm text-ink-muted">
                                Belum ada data alat camping di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
