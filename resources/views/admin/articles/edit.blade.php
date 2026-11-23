<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Editorial"
        title="Edit Article"
        subtitle="Update headline, story content, or cover photography."
        actionLabel="&larr; Back to Articles"
        :actionHref="route('admin.articles.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="max-w-3xl bg-white rounded-3xl border border-neutral-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 p-8">
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-2">
                    Article Title <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required
                       class="w-full rounded-xl border-neutral-300 bg-neutral-50/60 focus:bg-white focus:border-neutral-950 focus:ring-neutral-950 text-sm py-3 px-4">
                @error('title')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-2">
                    Slug <span class="text-neutral-400 font-normal normal-case">(optional)</span>
                </label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $article->slug) }}"
                       class="w-full rounded-xl border-neutral-300 bg-neutral-50/60 focus:bg-white focus:border-neutral-950 focus:ring-neutral-950 text-sm py-3 px-4 font-mono">
                @error('slug')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-2">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea id="content" name="content" rows="8" required
                          class="w-full rounded-xl border-neutral-300 bg-neutral-50/60 focus:bg-white focus:border-neutral-950 focus:ring-neutral-950 text-sm py-3 px-4 leading-relaxed">{{ old('content', $article->content) }}</textarea>
                @error('content')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-neutral-600 mb-2">
                    Cover Image <span class="text-neutral-400 font-normal normal-case">(leave empty to keep current)</span>
                </label>

                @if ($article->image_path)
                    <div class="mb-3 flex items-center gap-4 p-3 rounded-2xl bg-neutral-50 border border-neutral-200/80">
                        <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-16 h-16 rounded-xl object-cover">
                        <div>
                            <p class="text-xs font-semibold text-neutral-800">Current Cover</p>
                            <p class="text-[11px] font-mono text-neutral-500">{{ $article->image_path }}</p>
                        </div>
                    </div>
                @endif

                <input type="file" id="image" name="image" accept="image/*"
                       class="block w-full text-sm text-neutral-600 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-neutral-950 file:text-white hover:file:bg-neutral-800 cursor-pointer border border-neutral-200 rounded-2xl p-2 bg-neutral-50/60">
                @error('image')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.articles.index') }}"
                   class="rounded-full border border-neutral-300 px-6 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-full bg-neutral-950 text-white px-7 py-2.5 text-sm font-semibold hover:bg-neutral-800 transition shadow-sm">
                    Update Article
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
