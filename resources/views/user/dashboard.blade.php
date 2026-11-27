<x-app-layout>
    <!-- Welcome Header -->
    <x-admin-hero
        eyebrow="Rawlseyyy Member Area"
        :title="'Selamat Datang, ' . Auth::user()->name . '!'"
        subtitle="Pantau penyewaan alat camping yang sedang berjalan, cek status persetujuan booking, dan kelola profil akun Anda."
        actionLabel="Eksplor Katalog Alat"
        :actionHref="route('katalog')"
    />

    @if (session('selected_item'))
        <div data-aos="fade-up" class="mb-8 rounded-3xl border border-meadow-300 bg-meadow-50/90 px-6 py-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <span class="w-9 h-9 rounded-2xl bg-meadow-500 text-white flex items-center justify-center shrink-0 font-bold">
                    ✓
                </span>
                <div>
                    <p class="text-sm font-bold text-ink">
                        Anda memilih untuk menyewa: {{ \Illuminate\Support\Str::headline(session('selected_item')) }}
                    </p>
                    <p class="text-xs text-ink-muted mt-1">
                        Form pemesanan lengkap beserta pemilihan tanggal sewa akan segera diaktifkan pada tahap berikutnya.
                    </p>
                </div>
            </div>
            <a href="{{ route('katalog') }}" class="inline-flex items-center justify-center shrink-0 rounded-full bg-meadow-500 text-white px-5 py-2 text-xs font-semibold hover:bg-meadow-600 transition">
                Pilih Alat Lain di Katalog
            </a>
        </div>
    @endif

    <!-- Member Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <a href="{{ route('user.rentals') }}" data-aos="fade-up" data-aos-delay="50" class="group bg-white rounded-3xl border border-canvas-border p-6 shadow-sm hover:shadow-md hover:border-meadow-400 transition-all duration-300 flex flex-col justify-between">
            <span class="text-xs uppercase tracking-widest text-ink-muted font-semibold">Penyewaan Aktif</span>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-ink">{{ $activeRentals }}</p>
                    <span class="text-xs font-medium text-ink-muted">Booking</span>
                </div>
                <p class="mt-2 text-xs text-meadow-700 font-medium">Lihat Riwayat Sewa &rarr;</p>
            </div>
        </a>

        <a href="{{ route('user.rentals') }}" data-aos="fade-up" data-aos-delay="100" class="group bg-white rounded-3xl border border-canvas-border p-6 shadow-sm hover:shadow-md hover:border-earth-400 transition-all duration-300 flex flex-col justify-between">
            <span class="text-xs uppercase tracking-widest text-ink-muted font-semibold">Menunggu Persetujuan</span>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-ink">{{ $pendingApprovals }}</p>
                    <span class="text-xs font-medium text-ink-muted">Booking</span>
                </div>
                <p class="mt-2 text-xs text-earth-700 font-medium">Sedang diverifikasi oleh staff</p>
            </div>
        </a>

        <a href="{{ route('user.rentals') }}" data-aos="fade-up" data-aos-delay="150" class="group bg-gradient-to-br from-meadow-500 to-meadow-600 text-white rounded-3xl border border-meadow-600 p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
            <span class="text-xs uppercase tracking-widest text-meadow-50 font-semibold">Total Riwayat Sewa</span>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-white">{{ $completedRentals }}</p>
                    <span class="text-xs font-medium text-meadow-50">Transaksi</span>
                </div>
                <p class="mt-2 text-xs text-white font-semibold">Buka Semua Riwayat &rarr;</p>
            </div>
        </a>
    </div>

    <!-- Single Full-Width Rental Summary -->
    <div data-aos="fade-up" data-aos-delay="150" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex items-center justify-between">
            <div>
                <h2 class="font-display text-xl text-ink">Ringkasan Penyewaan Terbaru</h2>
                <p class="text-xs text-ink-muted mt-0.5">Status sewa terkini untuk akun Anda</p>
            </div>
            <a href="{{ route('user.rentals') }}" class="rounded-full bg-meadow-500 text-white px-4 py-1.5 text-xs font-semibold hover:bg-meadow-600 transition">
                Lihat Semua Riwayat
            </a>
        </div>
        <div class="px-7 py-8 text-center text-sm text-ink-muted">
            Ringkasan penyewaan Anda tersedia di halaman <a href="{{ route('profile.edit', ['tab' => 'rentals']) }}" class="font-semibold text-meadow-700 underline">Profil &amp; Riwayat Sewa</a>.
        </div>
    </div>
</x-app-layout>
