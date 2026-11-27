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
    <div data-aos="fade-up" class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden mb-8">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex items-center justify-between">
            <div>
                <h2 class="font-display text-xl text-ink">Penyewa Belum Mengembalikan (Ongoing)</h2>
                <p class="text-xs text-ink-muted mt-0.5">Transaksi melewati batas tanggal kembali ditandai merah beserta estimasi denda keterlambatan harian</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-skyblock-100 text-skyblock-700 text-xs font-bold">{{ $ongoingBookings->count() }} Aktif</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kode &amp; Penyewa</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Jadwal Kembali</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Status Waktu &amp; Estimasi Denda</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
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
                                <p class="font-mono text-sm font-bold text-ink">{{ $booking->booking_code }}</p>
                                <p class="text-xs text-ink-muted">{{ $booking->contact_name ?? $booking->user?->name }} &middot; {{ $booking->contact_phone ?? $booking->user?->phone }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold {{ $isOverdue ? 'text-red-700' : 'text-ink' }}">
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
                                    <span class="inline-flex px-3 py-1 rounded-full bg-earth-100 text-earth-800 text-xs font-bold">
                                        Jatuh Tempo Hari Ini
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-meadow-50 text-meadow-700 text-xs font-semibold">
                                        Aman (H-{{ $diffDays }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="px-4 py-2 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                                    Proses Kembali
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-ink-muted">
                                Tidak ada alat yang sedang dalam masa sewa saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: Riwayat Sudah Dikembalikan -->
    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70">
            <h2 class="font-display text-xl text-ink">Riwayat Pengembalian Selesai</h2>
            <p class="text-xs text-ink-muted mt-0.5">Rekap ketepatan waktu pengembalian, kondisi alat, dan denda yang tercatat</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kode &amp; Penyewa</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Batas vs Aktual</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kondisi</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Ketepatan &amp; Denda</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($returnedBookings as $booking)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-mono text-sm font-bold text-ink">{{ $booking->booking_code }}</p>
                                <p class="text-xs text-ink-muted">{{ $booking->contact_name ?? $booking->user?->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs text-ink-muted">
                                <p>Batas: {{ $booking->end_date?->format('d M Y') }}</p>
                                <p class="font-semibold text-ink">Kembali: {{ $booking->returned_at?->format('d M Y') ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold uppercase text-ink">
                                {{ str_replace('_', ' ', $booking->return_condition ?? 'baik') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($booking->is_late || $booking->late_days > 0 || $booking->late_fee > 0)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-50 text-red-700 border border-red-200 text-xs font-bold">
                                        Terlambat {{ $booking->late_days }} Hari + Denda Rp {{ number_format($booking->late_fee, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-meadow-50 text-meadow-700 border border-meadow-200 text-xs font-bold">
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
                                <a href="{{ route('staff.bookings.show', $booking) }}" class="text-xs font-semibold text-meadow-700 hover:text-meadow-600 underline">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-ink-muted">
                                Belum ada riwayat pengembalian selesai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($returnedBookings->hasPages())
            <div class="px-6 py-4 border-t border-canvas-border/70">
                {{ $returnedBookings->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
