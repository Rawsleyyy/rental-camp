<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch / Edit Artikel"
        :title="'Edit: ' . $article->title"
        subtitle="Perbarui konten edukasi dan gambar artikel."
        actionLabel="← Kembali"
        :actionHref="route('staff.articles.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8 max-w-3xl">
        <form method="POST" action="{{ route('staff.articles.update', $article) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Judul Artikel *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">
            </div>

            <div>
                <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Isi Konten *</label>
                <textarea id="content" name="content" rows="8" required class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">{{ old('content', $article->content) }}</textarea>
            </div>

            <div>
                <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Ganti Gambar Cover (Opsional)</label>
                @if ($article->image_path)
                    <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-32 h-20 object-cover rounded-2xl border border-canvas-border mb-2">
                @endif
                <input type="file" id="image" name="image" accept="image/*" class="block w-full text-xs text-ink-muted file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-meadow-500 file:text-white hover:file:bg-meadow-600 border border-canvas-border rounded-xl p-1.5 bg-canvas-warm/40">
            </div>

            <div class="pt-3 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                    Simpan Perubahan
                </button>
                <a href="{{ route('staff.articles.index') }}" class="px-5 py-2.5 rounded-full border border-canvas-border text-sm font-semibold text-ink-muted hover:bg-canvas-warm transition">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
