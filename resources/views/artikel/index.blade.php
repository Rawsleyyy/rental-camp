<x-public-layout title="Artikel & Tips Camping — Rawlseyyy">
    <section class="bg-neutral-950 text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-400 mb-2">Jurnal Outdoor</p>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white">Artikel &amp; Panduan Camping</h1>
            <p class="text-neutral-300 text-sm max-w-2xl mx-auto mt-3">
                Pelajari tips memilih peralatan, daftar perlengkapan wajib, hingga panduan berkemah nyaman bersama Rawlseyyy.
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($articles as $article)
                    <article class="bg-white rounded-3xl overflow-hidden border border-neutral-200/80 shadow-sm hover:shadow-lg transition flex flex-col justify-between">
                        <div>
                            <div class="aspect-[16/10] bg-neutral-100 overflow-hidden">
                                <img
                                    src="{{ $article->image_path ? asset('storage/' . $article->image_path) : 'https://images.unsplash.com/photo-1476041800959-2f6bb412c8ce?auto=format&fit=crop&w=800&q=80' }}"
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>
                            <div class="p-6">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-amber-600">
                                    {{ $article->created_at?->format('d M Y') }} &middot; {{ $article->user->name ?? 'Admin Rawlseyyy' }}
                                </p>
                                <h2 class="mt-2 text-xl font-bold text-neutral-950">{{ $article->title }}</h2>
                                <p class="mt-2.5 text-sm text-neutral-500 leading-relaxed line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 140) }}
                                </p>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <a href="{{ route('artikel.show', $article->slug) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-neutral-950 hover:text-amber-600 transition">
                                Baca Selengkapnya &rarr;
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 bg-white rounded-3xl border border-neutral-200 p-12 text-center text-sm text-neutral-500">
                        Belum ada artikel yang diterbitkan.
                    </div>
                @endforelse
            </div>

            @if ($articles->hasPages())
                <div class="mt-10">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
