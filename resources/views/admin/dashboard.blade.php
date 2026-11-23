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
        <a href="{{ route('admin.items.index') }}" data-aos="fade-up" data-aos-delay="50" class="group bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm hover:shadow-md hover:border-amber-400/60 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Total Alat Tersedia</span>
                <span class="w-9 h-9 rounded-2xl bg-neutral-100 flex items-center justify-center text-neutral-700 group-hover:bg-neutral-950 group-hover:text-amber-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-neutral-950">{{ $totalAvailableTools }}</p>
                    <span class="text-xs font-medium text-neutral-500">Unit</span>
                </div>
                <p class="mt-2 text-xs text-emerald-600 font-medium">Lihat Master Alat &rarr;</p>
            </div>
        </a>

        <a href="{{ route('admin.bookings.index') }}" data-aos="fade-up" data-aos-delay="100" class="group bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm hover:shadow-md hover:border-amber-400/60 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Menunggu Persetujuan</span>
                <span class="w-9 h-9 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-neutral-950 group-hover:text-amber-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-neutral-950">{{ $pendingApprovals }}</p>
                    <span class="text-xs font-medium text-neutral-500">Booking</span>
                </div>
                <p class="mt-2 text-xs text-amber-600 font-medium">Buka Transaksi Sewa &rarr;</p>
            </div>
        </a>

        <a href="{{ route('admin.bookings.index') }}" data-aos="fade-up" data-aos-delay="150" class="group bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm hover:shadow-md hover:border-amber-400/60 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Penyewaan Aktif</span>
                <span class="w-9 h-9 rounded-2xl bg-neutral-100 flex items-center justify-center text-neutral-700 group-hover:bg-neutral-950 group-hover:text-amber-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-neutral-950">{{ $activeRentals }}</p>
                    <span class="text-xs font-medium text-neutral-500">Transaksi</span>
                </div>
                <p class="mt-2 text-xs text-neutral-600 font-medium">Pantau Transaksi &rarr;</p>
            </div>
        </a>

        <a href="{{ route('admin.damage-reports.index') }}" data-aos="fade-up" data-aos-delay="200" class="group bg-neutral-950 text-white rounded-3xl border border-neutral-800 p-6 shadow-sm hover:shadow-md hover:border-amber-400/60 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Laporan Kerusakan</span>
                <span class="w-9 h-9 rounded-2xl bg-white/10 flex items-center justify-center text-amber-400 group-hover:bg-amber-400 group-hover:text-neutral-950 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-white">{{ $damageReports }}</p>
                    <span class="text-xs font-medium text-neutral-400">Unit</span>
                </div>
                <p class="mt-2 text-xs text-amber-400 font-medium">Buka Halaman Kerusakan &rarr;</p>
            </div>
        </a>
    </div>

    <!-- Single Full-Width Recent Equipment Table -->
    <div data-aos="fade-up" data-aos-delay="150" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Inventaris Alat Camping Terbaru</h2>
                <p class="text-xs text-neutral-500 mt-0.5">
                    {{ $categoryCount }} kategori &middot; {{ $itemCount }} alat &middot; {{ $articleCount }} artikel &middot; {{ $userCount }} akun terdaftar
                </p>
            </div>
            <a href="{{ route('admin.items.index') }}" class="inline-flex items-center justify-center self-start sm:self-auto rounded-full bg-neutral-950 text-white px-5 py-2 text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                Lihat Semua Master Alat
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Nama Alat</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kategori</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Stok</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Tarif / Hari</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($recentItems as $item)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-neutral-100 overflow-hidden shrink-0 flex items-center justify-center border border-neutral-200/60">
                                        @if ($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[10px] uppercase tracking-wider text-neutral-400">Gear</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-neutral-950">{{ $item->name }}</p>
                                        <p class="text-xs font-mono text-neutral-400">{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-600">
                                {{ $item->category->name ?? 'Tanpa Kategori' }}
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $item->total_stock <= 3 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $item->total_stock }} Unit
                                </span>
                            </td>
                            <td class="px-7 py-4 text-right text-sm font-semibold text-neutral-950">
                                Rp {{ number_format($item->daily_rate, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-7 py-10 text-center text-sm text-neutral-500">
                                Belum ada data alat camping di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
