<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Inventaris Staff"
        title="Kelola Data Alat Camping"
        subtitle="Tambah alat baru, perbarui stok tersedia, harga sewa harian, paket sewa, dan foto perlengkapan."
        actionLabel="+ Tambah Alat"
        :actionHref="route('staff.items.create')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70">
            <h2 class="font-display text-xl text-ink">Daftar Alat &amp; Paket Sewa</h2>
            <p class="text-xs text-ink-muted mt-0.5">{{ $items->total() }} item terdaftar di Rawlseyyy</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Alat</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Kategori</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Tipe</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Stok</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Tarif / Hari</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($items as $item)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-canvas-warm border border-canvas-border overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-ink-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-ink">{{ $item->name }}</p>
                                        <p class="text-xs font-mono text-ink-muted">{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-ink-muted">{{ $item->category->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $item->is_package ? 'bg-earth-100 text-earth-800' : 'bg-canvas-warm border border-canvas-border text-ink-muted' }}">
                                    {{ $item->is_package ? 'Paket Sewa' : 'Satuan' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-ink">{{ $item->total_stock }} Unit</td>
                            <td class="px-6 py-4 text-sm font-semibold text-ink">Rp {{ number_format($item->daily_rate, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('staff.items.edit', $item) }}" class="px-3.5 py-1.5 rounded-full border border-canvas-border text-xs font-semibold text-ink hover:bg-meadow-500 hover:text-white hover:border-meadow-500 transition">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('staff.items.destroy', $item) }}" onsubmit="return confirm('Hapus alat ini dari inventaris?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3.5 py-1.5 rounded-full border border-red-200 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-ink-muted">Belum ada data alat camping.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="px-6 py-4 border-t border-canvas-border/70">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
