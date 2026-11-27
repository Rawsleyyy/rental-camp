<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch Staff / Operasional"
        title="Data Alat Camping"
        subtitle="Cek stok tersedia, kategori perlengkapan, dan tarif sewa harian seluruh peralatan camping Pack & Pitch."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('staff.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">Daftar Inventaris Alat Camping</h2>
                <p class="text-xs text-ink-muted mt-0.5">{{ $items->total() }} perlengkapan terdaftar di katalog</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Alat Camping</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kategori</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Total Stok</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Tarif / Hari</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($items as $item)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-canvas-warm border border-canvas-border overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[10px] uppercase tracking-wider text-ink-muted">Gear</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-ink">{{ $item->name }}</p>
                                        <p class="text-xs font-mono text-ink-muted">{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted">{{ $item->category->name ?? '-' }}</td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $item->total_stock <= 3 ? 'bg-earth-100 text-earth-700' : 'bg-meadow-100 text-meadow-700' }}">
                                    {{ $item->total_stock }} Unit
                                </span>
                            </td>
                            <td class="px-7 py-4 text-right text-sm font-semibold text-ink">
                                Rp {{ number_format($item->daily_rate, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-7 py-12 text-center text-sm text-ink-muted">
                                Belum ada data alat camping terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="px-7 py-4 border-t border-canvas-border/70 bg-canvas-warm/50">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
