<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Inspeksi"
        title="Laporan Kerusakan Alat"
        subtitle="Tinjau seluruh laporan kerusakan perlengkapan pasca pengembalian, biaya denda perbaikan, dan status pelunasannya."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('admin.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">Daftar Kerusakan Peralatan</h2>
                <p class="text-xs text-ink-muted mt-0.5">{{ $damageReports->total() }} laporan kerusakan tercatat</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kode Booking</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Alat &amp; Penyewa</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Keterangan Kerusakan</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Denda</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($damageReports as $report)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4 text-sm font-mono font-semibold text-ink">
                                {{ $report->booking->booking_code ?? '-' }}
                            </td>
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-ink">{{ $report->item->name ?? 'Alat Camping' }}</p>
                                <p class="text-xs text-ink-muted">{{ $report->booking->user->name ?? '-' }}</p>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted max-w-md">
                                {{ $report->description }}
                            </td>
                            <td class="px-7 py-4 text-sm font-semibold text-ink">
                                Rp {{ number_format($report->penalty_fee, 0, ',', '.') }}
                            </td>
                            <td class="px-7 py-4 text-right">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $report->is_paid ? 'bg-meadow-50 text-meadow-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $report->is_paid ? 'Lunas' : 'Belum Dibayar' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-7 py-10 text-center text-sm text-ink-muted">
                                Belum ada laporan kerusakan peralatan yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($damageReports->hasPages())
            <div class="px-7 py-4 border-t border-canvas-border/70 bg-canvas-warm/50">
                {{ $damageReports->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
