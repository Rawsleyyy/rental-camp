<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::with('user')->orderByDesc('id')->paginate(12);

        return view('staff.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('staff.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $slug = $this->generateUniqueSlug(
            Article::class,
            ($validated['slug'] ?? null) ?: $validated['title']
        );

        $imagePath = $request->hasFile('image')
            ? $this->storePublicImage($request->file('image'), 'articles')
            : null;

        $article = Article::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'image_path' => $imagePath,
        ]);

        ActivityLogger::log('CREATE_ARTICLE', "Menerbitkan artikel baru: {$article->title}.");

        return redirect()
            ->route('staff.articles.index')
            ->with('success', "Artikel \"{$article->title}\" berhasil diterbitkan.");
    }

    public function edit(Article $article): View
    {
        return view('staff.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('articles', 'slug')->ignore($article->id)],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $slug = $this->generateUniqueSlug(
            Article::class,
            ($validated['slug'] ?? null) ?: $validated['title'],
            $article->id
        );

        $imagePath = $article->image_path;
        if ($request->hasFile('image')) {
            $imagePath = $this->storePublicImage($request->file('image'), 'articles', $article->image_path);
        }

        $article->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'image_path' => $imagePath,
        ]);

        ActivityLogger::log('UPDATE_ARTICLE', "Memperbarui artikel: {$article->title}.");

        return redirect()
            ->route('staff.articles.index')
            ->with('success', "Artikel \"{$article->title}\" berhasil diperbarui.");
    }

    public function destroy(Article $article): RedirectResponse
    {
        $title = $article->title;
        $this->deletePublicImage($article->image_path);
        $article->delete();

        ActivityLogger::log('DELETE_ARTICLE', "Menghapus artikel: {$title}.");

        return redirect()
            ->route('staff.articles.index')
            ->with('success', "Artikel \"{$title}\" berhasil dihapus.");
    }
}
