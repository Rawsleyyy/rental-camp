<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch Staff / Operasional"
        title="Data Artikel & Panduan"
        subtitle="Daftar artikel edukasi pendakian dan checklist peralatan yang ditayangkan untuk pengunjung Pack & Pitch."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('staff.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-canvas-border/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-display text-xl text-ink">Semua Artikel Panduan</h2>
                <p class="text-xs text-ink-muted mt-0.5">{{ $articles->total() }} artikel tersimpan di database</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-canvas-border/60">
                <thead class="bg-canvas-warm/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Judul Artikel</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Slug</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Penulis</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-ink-muted">Tanggal Terbit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-canvas-border/60 bg-white">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-ink">{{ $article->title }}</p>
                                <p class="text-xs text-ink-muted mt-0.5 line-clamp-1">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 85) }}</p>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-canvas-warm border border-canvas-border px-3 py-1 text-xs font-mono text-ink-muted">
                                    {{ $article->slug }}
                                </span>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted">{{ $article->user->name ?? 'Admin' }}</td>
                            <td class="px-7 py-4 text-right text-xs text-ink-muted">{{ $article->created_at?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-ink">Tips Memilih Tenda Camping</p>
                                <p class="text-xs text-ink-muted mt-0.5">Panduan menyesuaikan kapasitas dan rating waterproof tenda.</p>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-canvas-warm border border-canvas-border px-3 py-1 text-xs font-mono text-ink-muted">tips-memilih-tenda</span>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted">Admin Pack &amp; Pitch</td>
                            <td class="px-7 py-4 text-right text-xs text-ink-muted">Aktif (Preview)</td>
                        </tr>
                        <tr class="hover:bg-canvas-warm/60 transition">
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-ink">Checklist Alat Camping</p>
                                <p class="text-xs text-ink-muted mt-0.5">Daftar perlengkapan wajib sebelum memulai pendakian.</p>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-canvas-warm border border-canvas-border px-3 py-1 text-xs font-mono text-ink-muted">checklist-alat-camping</span>
                            </td>
                            <td class="px-7 py-4 text-sm text-ink-muted">Admin Pack &amp; Pitch</td>
                            <td class="px-7 py-4 text-right text-xs text-ink-muted">Aktif (Preview)</td>
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
