<x-public-layout :title="$article->title . ' — Rawlseyyy'">
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('artikel.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-neutral-500 hover:text-neutral-950 mb-6">
                &larr; Kembali ke Daftar Artikel
            </a>

            <article class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm overflow-hidden">
                <div class="aspect-[16/9] bg-neutral-100 overflow-hidden">
                    <img
                        src="{{ $article->image_path ? asset('storage/' . $article->image_path) : 'https://images.unsplash.com/photo-1476041800959-2f6bb412c8ce?auto=format&fit=crop&w=1200&q=80' }}"
                        alt="{{ $article->title }}"
                        class="w-full h-full object-cover"
                    >
                </div>

                <div class="p-6 sm:p-10">
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-600">
                        {{ $article->created_at?->format('d M Y') }} &middot; Ditulis oleh {{ $article->user->name ?? 'Admin Rawlseyyy' }}
                    </p>
                    <h1 class="mt-3 font-serif text-3xl sm:text-4xl font-bold text-neutral-950 leading-tight">
                        {{ $article->title }}
                    </h1>

                    <div class="mt-6 text-neutral-700 text-base leading-relaxed whitespace-pre-line">
                        {{ $article->content }}
                    </div>

                    <div class="mt-10 pt-6 border-t border-neutral-100 flex flex-wrap items-center justify-between gap-4">
                        <p class="text-sm font-medium text-neutral-600">Siap berangkat ke alam bebas? Cek perlengkapan yang tersedia di katalog kami.</p>
                        <a href="{{ route('katalog') }}" class="px-6 py-2.5 rounded-full bg-neutral-950 text-white text-xs font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                            Lihat Katalog Alat &rarr;
                        </a>
                    </div>
                </div>
            </article>

            @if ($relatedArticles->isNotEmpty())
                <div class="mt-12">
                    <h2 class="font-serif text-2xl font-bold text-neutral-950 mb-5">Artikel Lainnya</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedArticles as $related)
                            <a href="{{ route('artikel.show', $related->slug) }}" class="bg-white rounded-2xl border border-neutral-200 p-5 hover:border-amber-400 transition block">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400">{{ $related->created_at?->format('d M Y') }}</p>
                                <h3 class="mt-1.5 text-sm font-bold text-neutral-950">{{ $related->title }}</h3>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
