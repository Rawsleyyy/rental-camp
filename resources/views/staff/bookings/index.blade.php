<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Staff Transaksi"
        title="Manajemen Transaksi &amp; Approval Sewa"
        subtitle="Kelola persetujuan sewa baru, verifikasi bukti transfer dengan kode unik, check-out alat, hingga proses pengembalian dan denda."
    />

    <x-admin-flash />

    <!-- Filter Status Tabs & Search -->
    <div data-aos="fade-up" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm p-5 mb-6 space-y-4">
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
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition {{ $status === $key ? 'bg-neutral-950 text-white' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200' }}"
                >
                    <span>{{ $tabInfo['label'] }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $status === $key ? 'bg-amber-400 text-neutral-950' : 'bg-white text-neutral-800' }}">
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
                class="flex-1 rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950"
            >
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-neutral-950 text-white text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                Cari Transaksi
            </button>
        </form>
    </div>

    <!-- Bookings Table -->
    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kode &amp; Jadwal</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Penyewa</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Total &amp; Kode Unik</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Status</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($bookings as $booking)
                        @php
                            $badgeMap = [
                                'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                'approved' => 'bg-blue-50 text-blue-800 border-blue-200',
                                'waiting_verification' => 'bg-purple-50 text-purple-800 border-purple-200',
                                'confirmed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                'ongoing' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                'returned' => 'bg-neutral-100 text-neutral-800 border-neutral-200',
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
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="font-mono text-sm font-bold text-neutral-950 hover:text-amber-600">
                                    {{ $booking->booking_code }}
                                </a>
                                <p class="text-xs text-neutral-500 mt-0.5">
                                    {{ $booking->start_date?->format('d M') }} &ndash; {{ $booking->end_date?->format('d M Y') }} ({{ $booking->total_days }} hari)
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-neutral-900">{{ $booking->contact_name ?? $booking->user?->name }}</p>
                                <p class="text-xs text-neutral-500">{{ $booking->contact_phone ?? $booking->user?->phone }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-neutral-950">
                                    Rp {{ number_format($booking->payable_total, 0, ',', '.') }}
                                </p>
                                @if ($booking->unique_code)
                                    <p class="text-[11px] font-mono text-amber-700">Kode unik: {{ $booking->unique_code }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-3 py-1 rounded-full border text-xs font-semibold {{ $badgeMap[$booking->status] ?? 'bg-neutral-100 text-neutral-800 border-neutral-200' }}">
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
                                            <button type="submit" class="px-3.5 py-1.5 rounded-full bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition">
                                                ACC
                                            </button>
                                        </form>
                                    @elseif ($booking->status === 'confirmed')
                                        <a href="{{ route('staff.bookings.show', $booking) }}" class="px-3.5 py-1.5 rounded-full bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                                            Cek &amp; Serahkan
                                        </a>
                                    @endif

                                    <a href="{{ route('staff.bookings.show', $booking) }}" class="px-4 py-1.5 rounded-full border border-neutral-300 text-xs font-semibold text-neutral-800 hover:bg-neutral-950 hover:text-white transition">
                                        Detail &amp; Proses
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-neutral-500">
                                Tidak ada data transaksi yang cocok dengan filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bookings->hasPages())
            <div class="px-6 py-4 border-t border-neutral-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
