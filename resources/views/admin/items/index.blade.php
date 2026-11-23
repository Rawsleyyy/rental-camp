<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Inventory"
        title="Camping Gear Collection"
        subtitle="Manage stock availability, daily rental rates, categories, and product imagery."
        actionLabel="+ New Item"
        :actionHref="route('admin.items.create')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">All Rental Items</h2>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $items->total() }} items across Rawlseyyy camping equipment</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Item</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Category</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Type</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Stock</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Daily Rate</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($items as $item)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-neutral-100 border border-neutral-200/70 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-neutral-950">{{ $item->name }}</p>
                                        <p class="text-xs font-mono text-neutral-400 mt-0.5">{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-700">
                                {{ $item->category->name ?? '-' }}
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $item->type === 'glamping' ? 'bg-neutral-950 text-white' : 'bg-neutral-100 text-neutral-700' }}">
                                    {{ ucfirst($item->type) }}
                                </span>
                            </td>
                            <td class="px-7 py-4">
                                <span class="text-sm font-semibold {{ $item->total_stock <= 3 ? 'text-amber-700' : 'text-neutral-900' }}">
                                    {{ $item->total_stock }} units
                                </span>
                            </td>
                            <td class="px-7 py-4 text-sm font-semibold text-neutral-950">
                                Rp {{ number_format($item->daily_rate, 0, ',', '.') }}
                                <span class="text-xs font-normal text-neutral-400">/day</span>
                            </td>
                            <td class="px-7 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.items.edit', $item) }}"
                                       class="rounded-full border border-neutral-200 px-4 py-1.5 text-xs font-medium text-neutral-700 hover:bg-neutral-950 hover:text-white hover:border-neutral-950 transition">
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
                            <td colspan="6" class="px-7 py-12 text-center text-sm text-neutral-500">
                                No items found. Click "+ New Item" to add your first rental unit.
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
