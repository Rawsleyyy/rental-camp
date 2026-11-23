<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Editorial"
        title="Camping Stories &amp; Field Guides"
        subtitle="Publish outdoor preparation checklists, hiking tips, and camping gear guides."
        actionLabel="+ New Article"
        :actionHref="route('admin.articles.create')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">All Articles</h2>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $articles->total() }} editorial guides published</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Article</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Slug</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Author</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Published</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-neutral-100 border border-neutral-200/70 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($article->image_path)
                                            <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="max-w-md">
                                        <p class="text-sm font-semibold text-neutral-950">{{ $article->title }}</p>
                                        <p class="text-xs text-neutral-500 mt-0.5 line-clamp-1">{{ Str::limit(strip_tags($article->content), 75) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-mono text-neutral-700">
                                    {{ $article->slug }}
                                </span>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-700">
                                {{ $article->user->name ?? 'Admin' }}
                            </td>
                            <td class="px-7 py-4 text-xs text-neutral-500">
                                {{ $article->created_at?->format('d M Y') }}
                            </td>
                            <td class="px-7 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.articles.edit', $article) }}"
                                       class="rounded-full border border-neutral-200 px-4 py-1.5 text-xs font-medium text-neutral-700 hover:bg-neutral-950 hover:text-white hover:border-neutral-950 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Delete this article?');">
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
                                No articles found. Click "+ New Article" to write your first field guide.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="px-7 py-4 border-t border-neutral-100 bg-neutral-50/50">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
