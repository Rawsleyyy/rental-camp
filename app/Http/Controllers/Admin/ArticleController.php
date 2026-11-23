<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::with('user')->latest()->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('admin.articles.create');
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

        Article::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'image_path' => $imagePath,
        ]);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
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

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->deletePublicImage($article->image_path);
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}
