<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch / Edit Alat"
        :title="'Edit ' . $item->name"
        subtitle="Perbarui stok, harga harian, kategori, dan foto perlengkapan camping."
        actionLabel="← Kembali"
        :actionHref="route('staff.items.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" class="bg-white rounded-3xl border border-canvas-border shadow-sm p-6 sm:p-8 max-w-3xl">
        <form method="POST" action="{{ route('staff.items.update', $item) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Nama Alat / Paket *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">
                </div>
                <div>
                    <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Kategori *</label>
                    <select id="category_id" name="category_id" required class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="total_stock" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Stok Total (Unit) *</label>
                    <input type="number" id="total_stock" name="total_stock" min="0" value="{{ old('total_stock', $item->total_stock) }}" required class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">
                </div>
                <div>
                    <label for="daily_rate" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Harga Sewa / Hari (Rp) *</label>
                    <input type="number" id="daily_rate" name="daily_rate" min="0" step="1000" value="{{ old('daily_rate', $item->daily_rate) }}" required class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">
                </div>
            </div>

            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_package" value="1" @checked(old('is_package', $item->is_package)) class="rounded border-canvas-border text-meadow-500 focus:ring-meadow-500">
                <span class="text-sm font-medium text-ink">Tandai sebagai Paket Sewa (bukan satuan)</span>
            </label>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Deskripsi Lengkap *</label>
                <textarea id="description" name="description" rows="4" required class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-sm text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500">{{ old('description', $item->description) }}</textarea>
            </div>

            <div>
                <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-1.5">Ganti Foto Alat (Opsional)</label>
                @if ($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-24 h-24 object-cover rounded-2xl border border-canvas-border mb-2">
                @endif
                <input type="file" id="image" name="image" accept="image/*" class="block w-full text-xs text-ink-muted file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-meadow-500 file:text-white hover:file:bg-meadow-600 border border-canvas-border rounded-xl p-1.5 bg-canvas-warm/40">
            </div>

            <div class="pt-3 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-meadow-500 text-white text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                    Simpan Perubahan
                </button>
                <a href="{{ route('staff.items.index') }}" class="px-5 py-2.5 rounded-full border border-canvas-border text-sm font-semibold text-ink-muted hover:bg-canvas-warm transition">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
