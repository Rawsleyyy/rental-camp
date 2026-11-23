<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Edukasi & Tips"
        title="Kelola Artikel Camping"
        subtitle="Publikasikan panduan mendaki, tips merawat tenda, dan checklist perlengkapan untuk halaman publik Rawlseyyy."
        actionLabel="+ Tulis Artikel"
        :actionHref="route('staff.articles.create')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100">
            <h2 class="font-serif text-xl text-neutral-950">Daftar Artikel</h2>
            <p class="text-xs text-neutral-500 mt-0.5">{{ $articles->total() }} artikel dipublikasikan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Judul Artikel</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Penulis</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Tanggal</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-neutral-950">{{ $article->title }}</p>
                                <p class="text-xs font-mono text-neutral-400">{{ $article->slug }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700">{{ $article->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs text-neutral-500">{{ $article->created_at?->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('staff.articles.edit', $article) }}" class="px-3.5 py-1.5 rounded-full border border-neutral-300 text-xs font-semibold text-neutral-800 hover:bg-neutral-950 hover:text-white transition">
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
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-neutral-500">Belum ada artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="px-6 py-4 border-t border-neutral-100">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
