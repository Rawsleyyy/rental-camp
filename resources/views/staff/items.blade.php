<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy Staff / Operasional"
        title="Data Alat Camping"
        subtitle="Cek stok tersedia, kategori perlengkapan, dan tarif sewa harian seluruh peralatan camping Rawlseyyy."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('staff.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Daftar Inventaris Alat Camping</h2>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $items->total() }} perlengkapan terdaftar di katalog</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Alat Camping</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Kategori</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Total Stok</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Tarif / Hari</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($items as $item)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-neutral-100 border border-neutral-200/70 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[10px] uppercase tracking-wider text-neutral-400">Gear</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-neutral-950">{{ $item->name }}</p>
                                        <p class="text-xs font-mono text-neutral-400">{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-700">{{ $item->category->name ?? '-' }}</td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $item->total_stock <= 3 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $item->total_stock }} Unit
                                </span>
                            </td>
                            <td class="px-7 py-4 text-right text-sm font-semibold text-neutral-950">
                                Rp {{ number_format($item->daily_rate, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-7 py-12 text-center text-sm text-neutral-500">
                                Belum ada data alat camping terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="px-7 py-4 border-t border-neutral-100 bg-neutral-50/50">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
