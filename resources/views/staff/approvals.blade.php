<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy Staff / Transaksi"
        title="Persetujuan Sewa Alat Camping"
        subtitle="Verifikasi booking yang baru masuk, periksa kesesuaian tanggal &amp; stok, lalu konfirmasi pesanan pelanggan."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('staff.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">Antrean Booking Menunggu Persetujuan</h2>
                <p class="text-xs text-ink-muted mt-0.5">{{ $bookings->total() }} pesanan pending di database</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kode Booking</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Penyewa</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Periode Sewa</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Total Biaya</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4 text-sm font-mono font-semibold text-ink">{{ $booking->booking_code }}</td>
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-ink">{{ $booking->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-ink-muted">{{ $booking->bookingItems->count() }} perlengkapan</p>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted">
                                {{ $booking->start_date?->format('d M Y') }} &ndash; {{ $booking->end_date?->format('d M Y') }}
                            </td>
                            <td class="px-7 py-4 text-sm font-semibold text-ink">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-7 py-4 text-right">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-earth-100 text-earth-800">
                                    Menunggu Persetujuan
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-7 py-10 text-center text-sm text-ink-muted">
                                Tidak ada antrean booking menunggu persetujuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bookings->hasPages())
            <div class="px-7 py-4 border-t border-canvas-border/70 bg-canvas-warm/50">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
