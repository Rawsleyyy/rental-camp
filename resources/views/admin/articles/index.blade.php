<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Editorial"
        title="Camping Stories &amp; Field Guides"
        subtitle="Publish outdoor preparation checklists, hiking tips, and camping gear guides."
        actionLabel="+ New Article"
        :actionHref="route('admin.articles.create')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">All Articles</h2>
                <p class="text-xs text-ink-muted mt-0.5">{{ $articles->total() }} editorial guides published</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Article</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Slug</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Author</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Published</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-canvas-warm border border-canvas-border overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($article->image_path)
                                            <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-ink-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="max-w-md">
                                        <p class="text-sm font-semibold text-ink">{{ $article->title }}</p>
                                        <p class="text-xs text-ink-muted mt-0.5 line-clamp-1">{{ Str::limit(strip_tags($article->content), 75) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-canvas-warm border border-canvas-border px-3 py-1 text-xs font-mono text-ink-muted">
                                    {{ $article->slug }}
                                </span>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted">
                                {{ $article->user->name ?? 'Admin' }}
                            </td>
                            <td class="px-7 py-4 text-xs text-ink-muted">
                                {{ $article->created_at?->format('d M Y') }}
                            </td>
                            <td class="px-7 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.articles.edit', $article) }}"
                                       class="rounded-full border border-canvas-border px-4 py-1.5 text-xs font-medium text-ink hover:bg-meadow-500 hover:text-white hover:border-meadow-500 transition">
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
                            <td colspan="5" class="px-7 py-12 text-center text-sm text-ink-muted">
                                No articles found. Click "+ New Article" to write your first field guide.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="px-7 py-4 border-t border-canvas-border/70 bg-canvas-warm/50">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
