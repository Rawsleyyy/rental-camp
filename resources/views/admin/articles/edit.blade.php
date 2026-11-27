<x-app-layout>
    <x-admin-hero
        eyebrow="Rawlseyyy / Editorial"
        title="Edit Article"
        subtitle="Update headline, story content, or cover photography."
        actionLabel="&larr; Back to Articles"
        :actionHref="route('admin.articles.index')"
    />

    <x-admin-flash />

    <div data-aos="fade-up" data-aos-delay="100" class="max-w-3xl bg-white rounded-3xl border border-canvas-border shadow-sm hover:shadow-md transition-shadow duration-300 p-8">
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                    Article Title <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required
                       class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4">
                @error('title')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                    Slug <span class="text-ink-muted/70 font-normal normal-case">(optional)</span>
                </label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $article->slug) }}"
                       class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4 font-mono">
                @error('slug')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea id="content" name="content" rows="8" required
                          class="w-full rounded-xl border-canvas-border bg-canvas-warm/60 text-ink focus:bg-white focus:border-meadow-500 focus:ring-meadow-500 text-sm py-3 px-4 leading-relaxed">{{ old('content', $article->content) }}</textarea>
                @error('content')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">
                    Cover Image <span class="text-ink-muted/70 font-normal normal-case">(leave empty to keep current)</span>
                </label>

                @if ($article->image_path)
                    <div class="mb-3 flex items-center gap-4 p-3 rounded-2xl bg-canvas-warm border border-canvas-border">
                        <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-16 h-16 rounded-xl object-cover">
                        <div>
                            <p class="text-xs font-semibold text-ink">Current Cover</p>
                            <p class="text-[11px] font-mono text-ink-muted">{{ $article->image_path }}</p>
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
                <a href="{{ route('admin.articles.index') }}"
                   class="rounded-full border border-canvas-border px-6 py-2.5 text-sm font-medium text-ink-muted hover:bg-canvas-warm transition">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-full bg-meadow-500 text-white px-7 py-2.5 text-sm font-semibold hover:bg-meadow-600 transition shadow-sm shadow-meadow-500/20">
                    Update Article
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
