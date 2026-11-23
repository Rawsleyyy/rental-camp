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
        <div data-aos="fade-up" class="mb-8 rounded-3xl border border-amber-300 bg-amber-50/90 px-6 py-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <span class="w-9 h-9 rounded-2xl bg-amber-400 text-neutral-950 flex items-center justify-center shrink-0 font-bold">
                    ✓
                </span>
                <div>
                    <p class="text-sm font-bold text-neutral-950">
                        Anda memilih untuk menyewa: {{ \Illuminate\Support\Str::headline(session('selected_item')) }}
                    </p>
                    <p class="text-xs text-neutral-600 mt-1">
                        Form pemesanan lengkap beserta pemilihan tanggal sewa akan segera diaktifkan pada tahap berikutnya.
                    </p>
                </div>
            </div>
            <a href="{{ route('katalog') }}" class="inline-flex items-center justify-center shrink-0 rounded-full bg-neutral-950 text-white px-5 py-2 text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                Pilih Alat Lain di Katalog
            </a>
        </div>
    @endif

    <!-- Member Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <a href="{{ route('user.rentals') }}" data-aos="fade-up" data-aos-delay="50" class="group bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm hover:shadow-md hover:border-amber-400/60 transition-all duration-300 flex flex-col justify-between">
            <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Penyewaan Aktif</span>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-neutral-950">{{ $activeRentals }}</p>
                    <span class="text-xs font-medium text-neutral-500">Booking</span>
                </div>
                <p class="mt-2 text-xs text-emerald-600 font-medium">Lihat Riwayat Sewa &rarr;</p>
            </div>
        </a>

        <a href="{{ route('user.rentals') }}" data-aos="fade-up" data-aos-delay="100" class="group bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm hover:shadow-md hover:border-amber-400/60 transition-all duration-300 flex flex-col justify-between">
            <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Menunggu Persetujuan</span>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-neutral-950">{{ $pendingApprovals }}</p>
                    <span class="text-xs font-medium text-neutral-500">Booking</span>
                </div>
                <p class="mt-2 text-xs text-amber-600 font-medium">Sedang diverifikasi oleh staff</p>
            </div>
        </a>

        <a href="{{ route('user.rentals') }}" data-aos="fade-up" data-aos-delay="150" class="group bg-neutral-950 text-white rounded-3xl border border-neutral-800 p-6 shadow-sm hover:shadow-md hover:border-amber-400/60 transition-all duration-300 flex flex-col justify-between">
            <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Total Riwayat Sewa</span>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-white">{{ $completedRentals }}</p>
                    <span class="text-xs font-medium text-neutral-400">Transaksi</span>
                </div>
                <p class="mt-2 text-xs text-amber-400 font-medium">Buka Semua Riwayat &rarr;</p>
            </div>
        </a>
    </div>

    <!-- Single Full-Width Rental Summary -->
    <div data-aos="fade-up" data-aos-delay="150" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex items-center justify-between">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Ringkasan Penyewaan Terbaru</h2>
                <p class="text-xs text-neutral-500 mt-0.5">Status sewa terkini untuk akun Anda</p>
            </div>
            <a href="{{ route('user.rentals') }}" class="rounded-full bg-neutral-950 text-white px-4 py-1.5 text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                Lihat Semua Riwayat
            </a>
        </div>
        <div class="px-7 py-8 text-center text-sm text-neutral-500">
            Ringkasan penyewaan Anda tersedia di halaman <a href="{{ route('profile.edit', ['tab' => 'rentals']) }}" class="font-semibold text-neutral-950 underline">Profil &amp; Riwayat Sewa</a>.
        </div>
    </div>
</x-app-layout>
