<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Categories"
        title="Camping Equipment Categories"
        subtitle="Organize your Rawlseyyy outdoor rental gear into curated collections."
        actionLabel="+ New Category"
        :actionHref="route('admin.categories.create')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">All Categories</h2>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $categories->total() }} total collections registered</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">#</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Category Name</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Slug</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Items Count</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4 text-sm text-neutral-400 font-medium">
                                {{ $categories->firstItem() + $loop->index }}
                            </td>
                            <td class="px-7 py-4">
                                <span class="text-sm font-semibold text-neutral-950">{{ $category->name }}</span>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-mono text-neutral-700">
                                    {{ $category->slug }}
                                </span>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-neutral-950 text-white px-3 py-1 text-xs font-medium">
                                    {{ $category->items_count }} items
                                </span>
                            </td>
                            <td class="px-7 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="rounded-full border border-neutral-200 px-4 py-1.5 text-xs font-medium text-neutral-700 hover:bg-neutral-950 hover:text-white hover:border-neutral-950 transition">
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
                            <td colspan="5" class="px-7 py-12 text-center text-sm text-neutral-500">
                                No categories found. Create your first category to start cataloging items.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="px-7 py-4 border-t border-neutral-100 bg-neutral-50/50">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
