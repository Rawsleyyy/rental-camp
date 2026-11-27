<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch / Inventory"
        title="Camping Gear Collection"
        subtitle="Manage stock availability, daily rental rates, categories, and product imagery."
        actionLabel="+ New Item"
        :actionHref="route('admin.items.create')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">All Rental Items</h2>
                <p class="text-xs text-ink-muted mt-0.5">{{ $items->total() }} items across Pack &amp; Pitch camping equipment</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Item</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Category</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Type</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Stock</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Daily Rate</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($items as $item)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-canvas-warm border border-canvas-border overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-ink-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-ink">{{ $item->name }}</p>
                                        <p class="text-xs font-mono text-ink-muted mt-0.5">{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted">
                                {{ $item->category->name ?? '-' }}
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $item->type === 'glamping' ? 'bg-earth-500 text-white' : 'bg-canvas-warm border border-canvas-border text-ink-muted' }}">
                                    {{ ucfirst($item->type) }}
                                </span>
                            </td>
                            <td class="px-7 py-4">
                                <span class="text-sm font-semibold {{ $item->total_stock <= 3 ? 'text-earth-700' : 'text-meadow-700' }}">
                                    {{ $item->total_stock }} units
                                </span>
                            </td>
                            <td class="px-7 py-4 text-sm font-semibold text-ink">
                                Rp {{ number_format($item->daily_rate, 0, ',', '.') }}
                                <span class="text-xs font-normal text-ink-muted">/day</span>
                            </td>
                            <td class="px-7 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.items.edit', $item) }}"
                                       class="rounded-full border border-canvas-border px-4 py-1.5 text-xs font-medium text-ink hover:bg-meadow-500 hover:text-white hover:border-meadow-500 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-full border border-red-200 px-4 py-1.5 text-xs font-medium text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-7 py-12 text-center text-sm text-ink-muted">
                                No items found. Click "+ New Item" to add your first rental unit.
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
