<x-app-layout>
    <x-admin-hero
        eyebrow="Pack & Pitch / Inventory"
        title="Add New Rental Item"
        subtitle="Register camping & outdoor equipment into the Pack & Pitch catalog."
        actionLabel="&larr; Back to Items"
        :actionHref="route('admin.items.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="max-w-3xl bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 p-8">
        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                        <option value="">Select category...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
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
                        <option value="equipment" @selected(old('type') === 'equipment')>Equipment</option>
                        <option value="glamping" @selected(old('type') === 'glamping')>Glamping</option>
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
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="e.g. Pack &amp; Pitch Dome Pro 4P"
                           class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                        Slug <span class="text-ink-muted/70 font-normal normal-case">(optional)</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                           placeholder="auto-generated if blank"
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
                    <input type="number" id="total_stock" name="total_stock" min="0" value="{{ old('total_stock', 1) }}" required
                           class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                    @error('total_stock')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="daily_rate" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                        Daily Rate (IDR) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="daily_rate" name="daily_rate" min="0" value="{{ old('daily_rate', 100000) }}" required
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
                          placeholder="Describe specifications, capacity, and included accessories..."
                          class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                    Product Image <span class="text-ink-muted/70 font-normal normal-case">(JPG, PNG, WEBP &middot; max 2MB)</span>
                </label>
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
                    Save Item
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
