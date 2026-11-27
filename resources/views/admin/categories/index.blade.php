<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch / Categories"
        title="Camping Equipment Categories"
        subtitle="Organize your Pack & Pitch outdoor rental gear into curated collections."
        actionLabel="+ New Category"
        :actionHref="route('admin.categories.create')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">All Categories</h2>
                <p class="text-xs text-ink-muted mt-0.5">{{ $categories->total() }} total collections registered</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">#</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Category Name</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Slug</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Items Count</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4 text-sm text-ink-muted font-medium">
                                {{ $categories->firstItem() + $loop->index }}
                            </td>
                            <td class="px-7 py-4">
                                <span class="text-sm font-semibold text-ink">{{ $category->name }}</span>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-canvas-warm border border-canvas-border px-3 py-1 text-xs font-mono text-ink-muted">
                                    {{ $category->slug }}
                                </span>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-meadow-100 text-meadow-700 px-3 py-1 text-xs font-semibold">
                                    {{ $category->items_count }} items
                                </span>
                            </td>
                            <td class="px-7 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="rounded-full border border-canvas-border px-4 py-1.5 text-xs font-medium text-ink hover:bg-meadow-500 hover:text-white hover:border-meadow-500 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category and all its items?');">
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
                            <td colspan="5" class="px-7 py-12 text-center text-sm text-ink-muted">
                                No categories found. Create your first category to start cataloging items.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="px-7 py-4 border-t border-canvas-border/70 bg-canvas-warm/50">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
