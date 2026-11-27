<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy Staff Panel"
        :title="'Selamat Datang di Workspace Staff, ' . Auth::user()->name . '!'"
        subtitle="Pantau antrean persetujuan sewa, verifikasi bukti transfer, check-out alat, dan pengembalian tepat waktu."
        actionLabel="Manajemen Transaksi"
        :actionHref="route('staff.bookings.index')"
    />

    <x-admin-flash />

    <!-- Real-Time Staff Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <a href="{{ route('staff.bookings.index', ['status' => 'pending']) }}" data-aos="fade-up" data-aos-delay="50" class="group bg-white rounded-3xl border border-canvas-border p-6 shadow-sm hover:shadow-md hover:border-earth-400 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-ink-muted font-semibold">Permintaan Sewa Baru</span>
                <span class="w-9 h-9 rounded-2xl bg-earth-100 flex items-center justify-center text-earth-700 group-hover:bg-earth-500 group-hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-ink">{{ $pendingCount }}</p>
                    <span class="text-xs font-medium text-ink-muted">Pending</span>
                </div>
                <p class="mt-2 text-xs text-earth-700 font-medium">Review &amp; ACC Sewa &rarr;</p>
            </div>
        </a>

        <a href="{{ route('staff.bookings.index', ['status' => 'waiting_verification']) }}" data-aos="fade-up" data-aos-delay="100" class="group bg-white rounded-3xl border border-canvas-border p-6 shadow-sm hover:shadow-md hover:border-skyblock-400 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-ink-muted font-semibold">Verifikasi Pembayaran</span>
                <span class="w-9 h-9 rounded-2xl bg-skyblock-100 flex items-center justify-center text-skyblock-700 group-hover:bg-skyblock-500 group-hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-ink">{{ $waitingVerificationCount }}</p>
                    <span class="text-xs font-medium text-ink-muted">Bukti Masuk</span>
                </div>
                <p class="mt-2 text-xs text-skyblock-700 font-medium">Cek Mutasi &amp; Bukti &rarr;</p>
            </div>
        </a>

        <a href="{{ route('staff.bookings.index', ['status' => 'ongoing']) }}" data-aos="fade-up" data-aos-delay="150" class="group bg-white rounded-3xl border border-canvas-border p-6 shadow-sm hover:shadow-md hover:border-meadow-400 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-ink-muted font-semibold">Sedang Disewa</span>
                <span class="w-9 h-9 rounded-2xl bg-meadow-100 flex items-center justify-center text-meadow-700 group-hover:bg-meadow-500 group-hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-ink">{{ $ongoingCount }}</p>
                    <span class="text-xs font-medium text-ink-muted">Ongoing</span>
                </div>
                <p class="mt-2 text-xs text-meadow-700 font-medium">Pantau Alat Keluar &rarr;</p>
            </div>
        </a>

        <a href="{{ route('staff.returns') }}" data-aos="fade-up" data-aos-delay="200" class="group {{ $overdueCount > 0 ? 'bg-red-600 text-white border-red-700' : 'bg-gradient-to-br from-meadow-600 to-meadow-500 text-white border-meadow-600' }} rounded-3xl border p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest {{ $overdueCount > 0 ? 'text-red-100' : 'text-meadow-50' }} font-semibold">Terlambat / Overdue</span>
                <span class="w-9 h-9 rounded-2xl bg-white/20 text-white flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-display text-4xl font-bold text-white">{{ $overdueCount }}</p>
                    <span class="text-xs font-medium {{ $overdueCount > 0 ? 'text-red-100' : 'text-meadow-50' }}">Transaksi</span>
                </div>
                <p class="mt-2 text-xs text-white font-semibold underline">Buka Laporan Pengembalian &rarr;</p>
            </div>
        </a>
    </div>

    <!-- Monitoring Pengembalian & Jatuh Tempo -->
    <div data-aos="fade-up" data-aos-delay="150" class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">Monitoring Pengembalian &amp; Jatuh Tempo</h2>
                <p class="text-xs text-ink-muted mt-0.5">Daftar semua penyewaan aktif (ongoing) beserta status batas waktu pengembalian</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('staff.scan') }}" class="rounded-full bg-meadow-500 text-white px-4 py-2 text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                    Scan QR Basecamp
                </a>
                <a href="{{ route('staff.returns') }}" class="rounded-full bg-earth-500 text-white px-4 py-2 text-xs font-semibold hover:bg-earth-600 transition">
                    Laporan Pengembalian
                </a>
                <a href="{{ route('staff.cancellations.index') }}" class="rounded-full border border-meadow-500/50 text-meadow-700 px-4 py-2 text-xs font-semibold hover:bg-meadow-50 transition">
                    Approval Pembatalan
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kode &amp; Penyewa</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Alat Disewa</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Batas Kembali</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Status Jatuh Tempo</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @php $today = today(); @endphp
                    @forelse ($ongoingBookings as $booking)
                        @php
                            $endDate = $booking->end_date->copy()->startOfDay();
                            $diffDays = (int) $today->diffInDays($endDate, false);
                            $isOverdue = $diffDays < 0;
                            $isDueToday = $diffDays === 0;
                        @endphp
                        <tr class="{{ $isOverdue ? 'bg-red-50/60 hover:bg-red-50' : 'hover:bg-canvas-warm/60' }} transition">
                            <td class="px-6 py-4">
                                <p class="font-mono text-sm font-bold text-ink">{{ $booking->booking_code }}</p>
                                <p class="text-xs text-ink-muted mt-0.5">{{ $booking->contact_name ?? $booking->user?->name }}</p>
                                <p class="text-[11px] text-ink-muted">{{ $booking->contact_phone ?? $booking->user?->phone }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs text-ink-muted">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($booking->bookingItems as $bi)
                                        <span class="px-2 py-0.5 rounded-lg bg-canvas-warm border border-canvas-border/60 text-ink">
                                            {{ $bi->item->name ?? 'Alat' }} (&times;{{ $bi->quantity }})
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium {{ $isOverdue ? 'text-red-700 font-bold' : 'text-ink' }}">
                                {{ $booking->end_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($isOverdue)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-600 text-white text-xs font-bold">
                                        Terlambat {{ abs($diffDays) }} Hari
                                    </span>
                                @elseif ($isDueToday)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-earth-100 text-earth-800 border border-earth-200 text-xs font-bold">
                                        Jatuh Tempo Hari Ini
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-meadow-50 text-meadow-700 border border-meadow-200 text-xs font-semibold">
                                        Aman (Sisa {{ $diffDays }} Hari)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="inline-flex px-4 py-2 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                    Proses Pengembalian
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-ink-muted">
                                Tidak ada penyewaan berstatus sedang berlangsung (ongoing) saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
