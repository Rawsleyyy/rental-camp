<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Staff Transaksi"
        title="Manajemen Transaksi &amp; Approval Sewa"
        subtitle="Kelola persetujuan sewa baru, verifikasi bukti transfer dengan kode unik, check-out alat, hingga proses pengembalian dan denda."
    />

    <x-admin-flash />

    <!-- Filter Status Tabs & Search -->
    <div data-aos="fade-up" class="bg-white rounded-3xl border border-canvas-border shadow-sm p-5 mb-6 space-y-4">
        <div class="flex flex-wrap gap-2">
            @php
                $tabs = [
                    'all' => ['label' => 'Semua', 'count' => $counts['all']],
                    'pending' => ['label' => 'Perlu ACC', 'count' => $counts['pending']],
                    'approved' => ['label' => 'Belum Bayar', 'count' => $counts['approved']],
                    'waiting_verification' => ['label' => 'Verifikasi Bayar', 'count' => $counts['waiting_verification']],
                    'confirmed' => ['label' => 'Siap Diambil', 'count' => $counts['confirmed']],
                    'ongoing' => ['label' => 'Sedang Disewa', 'count' => $counts['ongoing']],
                    'returned' => ['label' => 'Selesai', 'count' => $counts['returned']],
                    'closed' => ['label' => 'Ditolak / Batal', 'count' => $counts['closed']],
                ];
            @endphp

            @foreach ($tabs as $key => $tabInfo)
                <a
                    href="{{ route('staff.bookings.index', ['status' => $key, 'q' => $search]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition {{ $status === $key ? 'bg-meadow-500 text-white shadow-sm shadow-meadow-500/20' : 'bg-canvas-warm text-ink-muted border border-canvas-border hover:bg-meadow-50 hover:text-meadow-700' }}"
                >
                    <span>{{ $tabInfo['label'] }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $status === $key ? 'bg-white/25 text-white' : 'bg-white text-ink' }}">
                        {{ $tabInfo['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('staff.bookings.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="status" value="{{ $status }}">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari Kode Booking (RWL-...) atau Nama / Email Penyewa..."
                class="flex-1 rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500"
            >
            <button type="submit" class="px-6 py-2.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                Cari Transaksi
            </button>
        </form>
    </div>

    <!-- Bookings Table -->
    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kode &amp; Jadwal</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Penyewa</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Total &amp; Kode Unik</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Status</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($bookings as $booking)
                        @php
                            $badgeMap = [
                                'pending' => 'bg-earth-50 text-earth-800 border-earth-200',
                                'approved' => 'bg-skyblock-50 text-skyblock-700 border-skyblock-200',
                                'waiting_verification' => 'bg-purple-50 text-purple-800 border-purple-200',
                                'confirmed' => 'bg-meadow-50 text-meadow-800 border-meadow-200',
                                'ongoing' => 'bg-skyblock-100 text-skyblock-700 border-skyblock-200',
                                'returned' => 'bg-canvas-warm text-ink border-canvas-border',
                                'rejected' => 'bg-red-50 text-red-800 border-red-200',
                                'cancelled' => 'bg-red-50 text-red-800 border-red-200',
                            ];
                            $labelMap = [
                                'pending' => 'Perlu ACC',
                                'approved' => 'Menunggu Transfer',
                                'waiting_verification' => 'Verifikasi Bayar',
                                'confirmed' => 'Siap Diambil',
                                'ongoing' => 'Sedang Disewa',
                                'returned' => 'Selesai / Kembali',
                                'rejected' => 'Ditolak',
                                'cancelled' => 'Dibatalkan',
                            ];
                        @endphp
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="font-mono text-sm font-bold text-ink hover:text-meadow-700">
                                    {{ $booking->booking_code }}
                                </a>
                                <p class="text-xs text-ink-muted mt-0.5">
                                    {{ $booking->start_date?->format('d M') }} &ndash; {{ $booking->end_date?->format('d M Y') }} ({{ $booking->total_days }} hari)
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-ink">{{ $booking->contact_name ?? $booking->user?->name }}</p>
                                <p class="text-xs text-ink-muted">{{ $booking->contact_phone ?? $booking->user?->phone }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-ink">
                                    Rp {{ number_format($booking->payable_total, 0, ',', '.') }}
                                </p>
                                @if ($booking->unique_code)
                                    <p class="text-[11px] font-mono text-earth-700">Kode unik: {{ $booking->unique_code }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-3 py-1 rounded-full border text-xs font-semibold {{ $badgeMap[$booking->status] ?? 'bg-canvas-warm text-ink border-canvas-border' }}">
                                    {{ $labelMap[$booking->status] ?? ucfirst($booking->status) }}
                                </span>
                                @if ($booking->status === 'ongoing' && now()->startOfDay()->gt($booking->end_date))
                                    <span class="block mt-1 text-[11px] font-bold text-red-600">
                                        OVERDUE {{ $booking->end_date->copy()->startOfDay()->diffInDays(now()->startOfDay()) }} Hari
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    @if ($booking->status === 'pending')
                                        <form method="POST" action="{{ route('staff.bookings.approve', $booking) }}">
                                            @csrf
                                            <button type="submit" class="px-3.5 py-1.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition">
                                                ACC
                                            </button>
                                        </form>
                                    @elseif ($booking->status === 'confirmed')
                                        <a href="{{ route('staff.bookings.show', $booking) }}" class="px-3.5 py-1.5 rounded-full bg-skyblock-600 text-white text-xs font-semibold hover:bg-skyblock-700 transition">
                                            Cek &amp; Serahkan
                                        </a>
                                    @endif

                                    <a href="{{ route('staff.bookings.show', $booking) }}" class="px-4 py-1.5 rounded-full border border-canvas-border text-xs font-semibold text-ink hover:bg-meadow-500 hover:text-white hover:border-meadow-500 transition">
                                        Detail &amp; Proses
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-ink-muted">
                                Tidak ada data transaksi yang cocok dengan filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bookings->hasPages())
            <div class="px-6 py-4 border-t border-canvas-border/70">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
