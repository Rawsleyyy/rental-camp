<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch / Edukasi & Tips"
        title="Kelola Artikel Camping"
        subtitle="Publikasikan panduan mendaki, tips merawat tenda, dan checklist perlengkapan untuk halaman publik Pack & Pitch."
        actionLabel="+ Tulis Artikel"
        :actionHref="route('staff.articles.create')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70">
            <h2 class="font-display text-xl text-ink">Daftar Artikel</h2>
            <p class="text-xs text-ink-muted mt-0.5">{{ $articles->total() }} artikel dipublikasikan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Judul Artikel</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Penulis</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Tanggal</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-ink">{{ $article->title }}</p>
                                <p class="text-xs font-mono text-ink-muted">{{ $article->slug }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-ink-muted">{{ $article->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs text-ink-muted">{{ $article->created_at?->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('staff.articles.edit', $article) }}" class="px-3.5 py-1.5 rounded-full border border-canvas-border text-xs font-semibold text-ink hover:bg-meadow-500 hover:text-white hover:border-meadow-500 transition">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('staff.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
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
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-ink-muted">Belum ada artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="px-6 py-4 border-t border-canvas-border/70">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
