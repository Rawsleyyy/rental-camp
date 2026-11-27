<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch / Inventory"
        title="Edit Item"
        subtitle="Update stock, daily rate, category, or product photography for {{ $item->name }}."
        actionLabel="&larr; Back to Items"
        :actionHref="route('admin.items.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="max-w-3xl bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 p-8">
        <form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                        Item Type <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                            class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                        <option value="equipment" @selected(old('type', $item->type) === 'equipment')>Equipment</option>
                        <option value="glamping" @selected(old('type', $item->type) === 'glamping')>Glamping</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                        Item Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required
                           class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                        Slug <span class="text-ink-muted/70 font-normal normal-case">(optional)</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $item->slug) }}"
                           class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4 font-mono">
                    @error('slug')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="total_stock" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                        Total Stock <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="total_stock" name="total_stock" min="0" value="{{ old('total_stock', $item->total_stock) }}" required
                           class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                    @error('total_stock')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="daily_rate" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                        Daily Rate (IDR) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="daily_rate" name="daily_rate" min="0" value="{{ old('daily_rate', $item->daily_rate) }}" required
                           class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                    @error('daily_rate')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea id="description" name="description" rows="4" required
                          class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">{{ old('description', $item->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                    Product Image <span class="text-ink-muted/70 font-normal normal-case">(leave empty to keep current)</span>
                </label>

                @if ($item->image_path)
                    <div class="mb-3 flex items-center gap-4 p-3 rounded-2xl bg-canvas-warm border border-canvas-border">
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-16 h-16 rounded-xl object-cover">
                        <div>
                            <p class="text-xs font-semibold text-ink">Current Image</p>
                            <p class="text-[11px] font-mono text-ink-muted">{{ $item->image_path }}</p>
                        </div>
                    </div>
                @endif

                <input type="file" id="image" name="image" accept="image/*"
                       class="block w-full text-sm text-ink-muted file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-meadow-500 file:text-white hover:file:bg-meadow-600 cursor-pointer border border-canvas-border rounded-2xl p-2 bg-canvas-warm/60">
                @error('image')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-canvas-border/70 flex items-center justify-end gap-3">
                <a href="{{ route('admin.items.index') }}"
                   class="rounded-full border border-canvas-border px-6 py-2.5 text-sm font-medium text-ink-muted hover:bg-canvas-warm transition">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-full bg-meadow-500 text-white px-7 py-2.5 text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                    Update Item
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
