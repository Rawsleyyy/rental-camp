<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Edit Alat"
        :title="'Edit ' . $item->name"
        subtitle="Perbarui stok, harga harian, kategori, dan foto perlengkapan camping."
        actionLabel="← Kembali"
        :actionHref="route('staff.items.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" class="bg-white rounded-3xl border border-neutral-200/80 shadow-sm p-6 sm:p-8 max-w-3xl">
        <form method="POST" action="{{ route('staff.items.update', $item) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">Nama Alat / Paket *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950">
                </div>
                <div>
                    <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">Kategori *</label>
                    <select id="category_id" name="category_id" required class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="total_stock" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">Stok Total (Unit) *</label>
                    <input type="number" id="total_stock" name="total_stock" min="0" value="{{ old('total_stock', $item->total_stock) }}" required class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950">
                </div>
                <div>
                    <label for="daily_rate" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">Harga Sewa / Hari (Rp) *</label>
                    <input type="number" id="daily_rate" name="daily_rate" min="0" step="1000" value="{{ old('daily_rate', $item->daily_rate) }}" required class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950">
                </div>
            </div>

            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_package" value="1" @checked(old('is_package', $item->is_package)) class="rounded border-neutral-300 text-neutral-950 focus:ring-amber-400">
                <span class="text-sm font-medium text-neutral-800">Tandai sebagai Paket Sewa (bukan satuan)</span>
            </label>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">Deskripsi Lengkap *</label>
                <textarea id="description" name="description" rows="4" required class="w-full rounded-xl border-neutral-300 text-sm focus:border-neutral-950 focus:ring-neutral-950">{{ old('description', $item->description) }}</textarea>
            </div>

            <div>
                <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-1.5">Ganti Foto Alat (Opsional)</label>
                @if ($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-24 h-24 object-cover rounded-2xl border border-neutral-200 mb-2">
                @endif
                <input type="file" id="image" name="image" accept="image/*" class="block w-full text-xs text-neutral-600 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-neutral-950 file:text-white border border-neutral-200 rounded-xl p-1.5">
            </div>

            <div class="pt-3 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-neutral-950 text-white text-sm font-semibold hover:bg-amber-400 hover:text-neutral-950 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('staff.items.index') }}" class="px-5 py-2.5 rounded-full border border-neutral-300 text-sm font-semibold text-neutral-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
