<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Tulis Artikel"
        title="Tambah Artikel Camping"
        subtitle="Bagikan tips mendaki dan panduan penggunaan alat kepada pengunjung Rawlseyyy."
        actionLabel="← Kembali"
        :actionHref="route('staff.articles.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm p-6 sm:p-8 max-w-3xl">
        <form method="POST" action="{{ route('staff.articles.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">Judul Artikel *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950">
            </div>

            <div>
                <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">Isi Konten *</label>
                <textarea id="content" name="content" rows="8" required class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950">{{ old('content') }}</textarea>
            </div>

            <div>
                <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">Gambar Cover (Opsional, Maks 2MB)</label>
                <input type="file" id="image" name="image" accept="image/*" class="block w-full text-xs text-neutral-600 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-neutral-950 file:text-white border border-neutral-200 rounded-xl p-1.5">
            </div>

            <div class="pt-3 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-neutral-950 text-white text-sm font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                    Terbitkan Artikel
                </button>
                <a href="{{ route('staff.articles.index') }}" class="px-5 py-2.5 rounded-full border border-neutral-300 text-sm font-semibold text-neutral-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
