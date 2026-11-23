<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy Staff / Transaksi"
        title="Persetujuan Sewa Alat Camping"
        subtitle="Verifikasi booking yang baru masuk, periksa kesesuaian tanggal &amp; stok, lalu konfirmasi pesanan pelanggan."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('staff.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Antrean Booking Menunggu Persetujuan</h2>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $bookings->total() }} pesanan pending di database</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kode Booking</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Penyewa</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Periode Sewa</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Total Biaya</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4 text-sm font-mono font-semibold text-neutral-950">{{ $booking->booking_code }}</td>
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-neutral-950">{{ $booking->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-neutral-500">{{ $booking->bookingItems->count() }} perlengkapan</p>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-600">
                                {{ $booking->start_date?->format('d M Y') }} &ndash; {{ $booking->end_date?->format('d M Y') }}
                            </td>
                            <td class="px-7 py-4 text-sm font-semibold text-neutral-950">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-7 py-4 text-right">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-amber-50 text-amber-700">
                                    Menunggu Persetujuan
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-7 py-10 text-center text-sm text-neutral-500">
                                Tidak ada antrean booking menunggu persetujuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bookings->hasPages())
            <div class="px-7 py-4 border-t border-neutral-100 bg-neutral-50/50">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
