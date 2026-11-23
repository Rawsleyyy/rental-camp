<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy Staff / Operasional"
        title="Data Artikel &amp; Panduan"
        subtitle="Daftar artikel edukasi pendakian dan checklist peralatan yang ditayangkan untuk pengunjung Rawlseyyy."
        actionLabel="&larr; Kembali ke Dashboard"
        :actionHref="route('staff.dashboard')"
    />

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="px-7 py-5 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl text-neutral-950">Semua Artikel Panduan</h2>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $articles->total() }} artikel tersimpan di database</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Judul Artikel</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Slug</th>
                        <th class="px-7 py-3.5 text-left text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Penulis</th>
                        <th class="px-7 py-3.5 text-right text-[11px] font-semibold uppercase tracking-widest text-neutral-500">Tanggal Terbit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-neutral-950">{{ $article->title }}</p>
                                <p class="text-xs text-neutral-500 mt-0.5 line-clamp-1">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 85) }}</p>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-mono text-neutral-700">
                                    {{ $article->slug }}
                                </span>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-700">{{ $article->user->name ?? 'Admin' }}</td>
                            <td class="px-7 py-4 text-right text-xs text-neutral-500">{{ $article->created_at?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-neutral-950">Tips Memilih Tenda Camping</p>
                                <p class="text-xs text-neutral-500 mt-0.5">Panduan menyesuaikan kapasitas dan rating waterproof tenda.</p>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-mono text-neutral-700">tips-memilih-tenda</span>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-700">Admin Rawlseyyy</td>
                            <td class="px-7 py-4 text-right text-xs text-neutral-500">Aktif (Preview)</td>
                        </tr>
                        <tr class="hover:bg-neutral-50/70 transition">
                            <td class="px-7 py-4">
                                <p class="text-sm font-semibold text-neutral-950">Checklist Alat Camping</p>
                                <p class="text-xs text-neutral-500 mt-0.5">Daftar perlengkapan wajib sebelum memulai pendakian.</p>
                            </td>
                            <td class="px-7 py-4">
                                <span class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-mono text-neutral-700">checklist-alat-camping</span>
                            </td>
                            <td class="px-7 py-4 text-sm text-neutral-700">Admin Rawlseyyy</td>
                            <td class="px-7 py-4 text-right text-xs text-neutral-500">Aktif (Preview)</td>
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
