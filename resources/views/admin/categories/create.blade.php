<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Categories"
        title="Create New Category"
        subtitle="Add a new classification for Rawlseyyy camping &amp; outdoor equipment."
        actionLabel="&larr; Back to Categories"
        :actionHref="route('admin.categories.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="max-w-2xl bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                    Category Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="e.g. Tenda &amp; Shelter"
                       required
                       class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                    Slug <span class="text-ink-muted/70 font-normal normal-case">(optional — auto-generated from name if blank)</span>
                </label>
                <input type="text"
                       id="slug"
                       name="slug"
                       value="{{ old('slug') }}"
                       placeholder="e.g. tenda-shelter"
                       class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4 font-mono">
                @error('slug')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-canvas-border/70 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}"
                   class="rounded-full border border-canvas-border px-6 py-2.5 text-sm font-medium text-ink-muted hover:bg-canvas-warm transition">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-full bg-meadow-500 text-white px-7 py-2.5 text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                    Save Category
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
