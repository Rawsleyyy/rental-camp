<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Laporan Pengembalian"
        title="Laporan Status Pengembalian Alat"
        subtitle="Pantau alat yang masih beredar di penyewa (termasuk yang melewati batas waktu/overdue) serta riwayat pengembalian."
        actionLabel="Manajemen Transaksi"
        :actionHref="route('staff.bookings.index')"
    />

    <x-admin-flash />

    <!-- Section 1: Belum Dikembalikan (Ongoing / Overdue) -->
    <div data-aos="fade-up" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm overflow-hidden mb-8">
        <div class="px-7 py-5 border-b border-neutral-100 flex items-center justify-between">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Penyewa Belum Mengembalikan (Ongoing)</h2>
                <p class="text-xs text-neutral-500 mt-0.5">Transaksi melewati batas tanggal kembali ditandai merah beserta estimasi denda keterlambatan harian</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold">{{ $ongoingBookings->count() }} Aktif</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kode &amp; Penyewa</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Jadwal Kembali</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Status Waktu &amp; Estimasi Denda</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @php $today = today(); @endphp
                    @forelse ($ongoingBookings as $booking)
                        @php
                            $endDate = $booking->end_date->copy()->startOfDay();
                            $diffDays = (int) $today->diffInDays($endDate, false);
                            $isOverdue = $diffDays < 0;
                            $overdueDays = $isOverdue ? abs($diffDays) : 0;
                            $estLateFee = (int) round(($booking->total_price / max(1, (int) $booking->total_days)) * $overdueDays);
                        @endphp
                        <tr class="{{ $isOverdue ? 'bg-red-50/70' : '' }}">
                            <td class="px-6 py-4">
                                <p class="font-mono text-sm font-bold text-neutral-950">{{ $booking->booking_code }}</p>
                                <p class="text-xs text-neutral-600">{{ $booking->contact_name ?? $booking->user?->name }} &middot; {{ $booking->contact_phone ?? $booking->user?->phone }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold {{ $isOverdue ? 'text-red-700' : 'text-neutral-800' }}">
                                {{ $booking->end_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($isOverdue)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-600 text-white text-xs font-bold">
                                        Overdue {{ $overdueDays }} Hari
                                    </span>
                                    <span class="block text-xs font-bold text-red-700 mt-1">
                                        Est. Denda: Rp {{ number_format($estLateFee, 0, ',', '.') }}
                                    </span>
                                @elseif ($diffDays === 0)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                                        Jatuh Tempo Hari Ini
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">
                                        Aman (H-{{ $diffDays }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="px-4 py-2 rounded-full bg-neutral-950 text-white text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                                    Proses Kembali
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-neutral-500">
                                Tidak ada alat yang sedang dalam masa sewa saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: Riwayat Sudah Dikembalikan -->
    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100">
            <h2 class="font-serif text-xl text-neutral-950">Riwayat Pengembalian Selesai</h2>
            <p class="text-xs text-neutral-500 mt-0.5">Rekap ketepatan waktu pengembalian, kondisi alat, dan denda yang tercatat</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kode &amp; Penyewa</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Batas vs Aktual</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kondisi</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Ketepatan &amp; Denda</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($returnedBookings as $booking)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-mono text-sm font-bold text-neutral-950">{{ $booking->booking_code }}</p>
                                <p class="text-xs text-neutral-600">{{ $booking->contact_name ?? $booking->user?->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs text-neutral-700">
                                <p>Batas: {{ $booking->end_date?->format('d M Y') }}</p>
                                <p class="font-semibold text-neutral-950">Kembali: {{ $booking->returned_at?->format('d M Y') ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold uppercase text-neutral-800">
                                {{ str_replace('_', ' ', $booking->return_condition ?? 'baik') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($booking->is_late || $booking->late_days > 0 || $booking->late_fee > 0)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-50 text-red-700 border border-red-200 text-xs font-bold">
                                        Terlambat {{ $booking->late_days }} Hari + Denda Rp {{ number_format($booking->late_fee, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                        Tepat Waktu
                                    </span>
                                @endif

                                @if ($booking->damage_fee > 0)
                                    <span class="block mt-1 text-xs font-semibold text-red-600">
                                        Denda Kerusakan: Rp {{ number_format($booking->damage_fee, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="text-xs font-semibold text-neutral-700 hover:text-neutral-950 underline">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-neutral-500">
                                Belum ada riwayat pengembalian selesai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($returnedBookings->hasPages())
            <div class="px-6 py-4 border-t border-neutral-100">
                {{ $returnedBookings->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
