<x-public-layout :title="$article->title . ' — Pack & Pitch'">
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('artikel.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-ink-muted hover:text-meadow-700 mb-6 transition">
                &larr; Kembali ke Daftar Artikel
            </a>

            <article class="bg-white rounded-3xl border border-canvas-border shadow-sm overflow-hidden">
                <div class="aspect-[16/9] bg-canvas-warm overflow-hidden">
                    <img
                        src="{{ $article->image_path ? asset('storage/' . $article->image_path) : 'https://images.unsplash.com/photo-1476041800959-2f6bb412c8ce?auto=format&fit=crop&w=1200&q=80' }}"
                        alt="{{ $article->title }}"
                        class="w-full h-full object-cover"
                    >
                </div>

                <div class="p-6 sm:p-10">
                    <p class="text-xs font-semibold uppercase tracking-widest text-earth-600">
                        {{ $article->created_at?->format('d M Y') }} &middot; Ditulis oleh {{ $article->user->name ?? 'Admin Pack & Pitch' }}
                    </p>
                    <h1 class="mt-3 font-display text-3xl sm:text-4xl font-bold text-ink leading-tight">
                        {{ $article->title }}
                    </h1>

                    <div class="mt-6 text-ink text-base leading-relaxed whitespace-pre-line">
                        {{ $article->content }}
                    </div>

                    <div class="mt-10 pt-6 border-t border-canvas-border/70 flex flex-wrap items-center justify-between gap-4">
                        <p class="text-sm font-medium text-ink-muted">Siap berangkat ke alam bebas? Cek perlengkapan yang tersedia di katalog kami.</p>
                        <a href="{{ route('katalog') }}" class="px-6 py-2.5 rounded-full bg-meadow-500 text-white text-xs font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                            Lihat Katalog Alat &rarr;
                        </a>
                    </div>
                </div>
            </article>

            @if ($relatedArticles->isNotEmpty())
                <div class="mt-12">
                    <h2 class="font-display text-2xl font-bold text-ink mb-5">Artikel Lainnya</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedArticles as $related)
                            <a href="{{ route('artikel.show', $related->slug) }}" class="bg-white rounded-2xl border border-canvas-border p-5 hover:border-meadow-500 transition block shadow-sm">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-earth-600">{{ $related->created_at?->format('d M Y') }}</p>
                                <h3 class="mt-1.5 text-sm font-bold text-ink">{{ $related->title }}</h3>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
