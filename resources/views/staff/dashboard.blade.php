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
        <a href="{{ route('staff.bookings.index', ['status' => 'pending']) }}" data-aos="fade-up" data-aos-delay="50" class="group bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm hover:shadow-md hover:border-amber-400/60 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Permintaan Sewa Baru</span>
                <span class="w-9 h-9 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-neutral-950 group-hover:text-amber-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-neutral-950">{{ $pendingCount }}</p>
                    <span class="text-xs font-medium text-neutral-500">Pending</span>
                </div>
                <p class="mt-2 text-xs text-amber-600 font-medium">Review &amp; ACC Sewa &rarr;</p>
            </div>
        </a>

        <a href="{{ route('staff.bookings.index', ['status' => 'waiting_verification']) }}" data-aos="fade-up" data-aos-delay="100" class="group bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm hover:shadow-md hover:border-purple-400/60 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Verifikasi Pembayaran</span>
                <span class="w-9 h-9 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:bg-neutral-950 group-hover:text-amber-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-neutral-950">{{ $waitingVerificationCount }}</p>
                    <span class="text-xs font-medium text-neutral-500">Bukti Masuk</span>
                </div>
                <p class="mt-2 text-xs text-purple-600 font-medium">Cek Mutasi &amp; Bukti &rarr;</p>
            </div>
        </a>

        <a href="{{ route('staff.bookings.index', ['status' => 'ongoing']) }}" data-aos="fade-up" data-aos-delay="150" class="group bg-white rounded-3xl border border-neutral-200/80 p-6 shadow-sm hover:shadow-md hover:border-indigo-400/60 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest text-neutral-400 font-semibold">Sedang Disewa</span>
                <span class="w-9 h-9 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-neutral-950 group-hover:text-amber-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-neutral-950">{{ $ongoingCount }}</p>
                    <span class="text-xs font-medium text-neutral-500">Ongoing</span>
                </div>
                <p class="mt-2 text-xs text-indigo-600 font-medium">Pantau Alat Keluar &rarr;</p>
            </div>
        </a>

        <a href="{{ route('staff.returns') }}" data-aos="fade-up" data-aos-delay="200" class="group {{ $overdueCount > 0 ? 'bg-red-600 text-white border-red-700' : 'bg-neutral-950 text-white border-neutral-800' }} rounded-3xl border p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-widest {{ $overdueCount > 0 ? 'text-red-100' : 'text-neutral-400' }} font-semibold">Terlambat / Overdue</span>
                <span class="w-9 h-9 rounded-2xl {{ $overdueCount > 0 ? 'bg-white/20 text-white' : 'bg-white/10 text-amber-400' }} flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="mt-5">
                <div class="flex items-baseline gap-2">
                    <p class="font-serif text-4xl font-bold text-white">{{ $overdueCount }}</p>
                    <span class="text-xs font-medium {{ $overdueCount > 0 ? 'text-red-100' : 'text-neutral-400' }}">Transaksi</span>
                </div>
                <p class="mt-2 text-xs {{ $overdueCount > 0 ? 'text-white underline' : 'text-amber-400' }} font-medium">Buka Laporan Pengembalian &rarr;</p>
            </div>
        </a>
    </div>

    <!-- Monitoring Pengembalian & Jatuh Tempo -->
    <div data-aos="fade-up" data-aos-delay="150" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Monitoring Pengembalian &amp; Jatuh Tempo</h2>
                <p class="text-xs text-neutral-500 mt-0.5">Daftar semua penyewaan aktif (ongoing) beserta status batas waktu pengembalian</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('staff.scan') }}" class="rounded-full bg-amber-400 text-neutral-950 px-4 py-2 text-xs font-bold hover:bg-amber-300 transition">
                    Scan QR Basecamp
                </a>
                <a href="{{ route('staff.returns') }}" class="rounded-full bg-neutral-950 text-white px-4 py-2 text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                    Laporan Pengembalian
                </a>
                <a href="{{ route('staff.cancellations.index') }}" class="rounded-full border border-neutral-200 text-neutral-800 px-4 py-2 text-xs font-semibold hover:border-neutral-950 transition">
                    Approval Pembatalan
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kode &amp; Penyewa</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Alat Disewa</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Batas Kembali</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Status Jatuh Tempo</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @php $today = today(); @endphp
                    @forelse ($ongoingBookings as $booking)
                        @php
                            $endDate = $booking->end_date->copy()->startOfDay();
                            $diffDays = (int) $today->diffInDays($endDate, false);
                            $isOverdue = $diffDays < 0;
                            $isDueToday = $diffDays === 0;
                        @endphp
                        <tr class="{{ $isOverdue ? 'bg-red-50/60 hover:bg-red-50' : 'hover:bg-neutral-50/70' }} transition">
                            <td class="px-6 py-4">
                                <p class="font-mono text-sm font-bold text-neutral-950">{{ $booking->booking_code }}</p>
                                <p class="text-xs text-neutral-600 mt-0.5">{{ $booking->contact_name ?? $booking->user?->name }}</p>
                                <p class="text-[11px] text-neutral-400">{{ $booking->contact_phone ?? $booking->user?->phone }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs text-neutral-700">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($booking->bookingItems as $bi)
                                        <span class="px-2 py-0.5 rounded-lg bg-neutral-100 text-neutral-800">
                                            {{ $bi->item->name ?? 'Alat' }} (&times;{{ $bi->quantity }})
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium {{ $isOverdue ? 'text-red-700 font-bold' : 'text-neutral-800' }}">
                                {{ $booking->end_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($isOverdue)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-600 text-white text-xs font-bold">
                                        Terlambat {{ abs($diffDays) }} Hari
                                    </span>
                                @elseif ($isDueToday)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold">
                                        Jatuh Tempo Hari Ini
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                                        Aman (Sisa {{ $diffDays }} Hari)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="inline-flex px-4 py-2 rounded-full bg-neutral-950 text-white text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                                    Proses Pengembalian
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-neutral-500">
                                Tidak ada penyewaan berstatus sedang berlangsung (ongoing) saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
