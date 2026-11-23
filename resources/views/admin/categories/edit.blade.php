<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Categories"
        title="Edit Category"
        subtitle="Refine category name or URL-friendly slug for {{ $category->name }}."
        actionLabel="&larr; Back to Categories"
        :actionHref="route('admin.categories.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="max-w-2xl bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 p-8">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-2">
                    Category Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $category->name) }}"
                       required
                       class="w-full rounded-xl border-neutral-300 bg-neutral-50/60 focus:bg-white focus:border-neutral-950 focus:ring-neutral-950 text-sm py-3 px-4">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-2">
                    Slug <span class="text-neutral-400 font-normal normal-case">(optional)</span>
                </label>
                <input type="text"
                       id="slug"
                       name="slug"
                       value="{{ old('slug', $category->slug) }}"
                       class="w-full rounded-xl border-neutral-300 bg-neutral-50/60 focus:bg-white focus:border-neutral-950 focus:ring-neutral-950 text-sm py-3 px-4 font-mono">
                @error('slug')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}"
                   class="rounded-full border border-neutral-300 px-6 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-full bg-neutral-950 text-white px-7 py-2.5 text-sm font-semibold hover:bg-neutral-800 transition shadow-sm">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
