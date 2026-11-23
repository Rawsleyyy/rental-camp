<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Inspeksi"
        title="Laporan Kerusakan Alat"
        subtitle="Tinjau seluruh laporan kerusakan perlengkapan pasca pengembalian, biaya denda perbaikan, dan status pelunasannya."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('admin.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Daftar Kerusakan Peralatan</h2>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $damageReports->total() }} laporan kerusakan tercatat</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kode Booking</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Alat &amp; Penyewa</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Keterangan Kerusakan</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Denda</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($damageReports as $report)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4 text-sm font-mono font-semibold text-neutral-950">
                                {{ $report->booking->booking_code ?? '-' }}
                            </td>
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-neutral-950">{{ $report->item->name ?? 'Alat Camping' }}</p>
                                <p class="text-xs text-neutral-500">{{ $report->booking->user->name ?? '-' }}</p>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-600 max-w-md">
                                {{ $report->description }}
                            </td>
                            <td class="px-7 py-4 text-sm font-semibold text-neutral-950">
                                Rp {{ number_format($report->penalty_fee, 0, ',', '.') }}
                            </td>
                            <td class="px-7 py-4 text-right">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $report->is_paid ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $report->is_paid ? 'Lunas' : 'Belum Dibayar' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-7 py-10 text-center text-sm text-neutral-500">
                                Belum ada laporan kerusakan peralatan yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($damageReports->hasPages())
            <div class="px-7 py-4 border-t border-neutral-100 bg-neutral-50/50">
                {{ $damageReports->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
