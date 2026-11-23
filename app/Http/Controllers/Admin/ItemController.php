<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(): View
    {
        $items = Item::with('category')->latest()->paginate(10);

        return view('admin.items.index', compact('items'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:items,slug'],
            'description' => ['required', 'string'],
            'type' => ['required', 'in:equipment,glamping'],
            'total_stock' => ['required', 'integer', 'min:0'],
            'daily_rate' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $slug = $this->generateUniqueSlug(
            Item::class,
            ($validated['slug'] ?? null) ?: $validated['name']
        );

        $imagePath = $request->hasFile('image')
            ? $this->storePublicImage($request->file('image'), 'items')
            : null;

        Item::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'type' => $validated['type'],
            'total_stock' => $validated['total_stock'],
            'daily_rate' => $validated['daily_rate'],
            'image_path' => $imagePath,
        ]);

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item created successfully.');
    }

    public function edit(Item $item): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('items', 'slug')->ignore($item->id)],
            'description' => ['required', 'string'],
            'type' => ['required', 'in:equipment,glamping'],
            'total_stock' => ['required', 'integer', 'min:0'],
            'daily_rate' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $slug = $this->generateUniqueSlug(
            Item::class,
            ($validated['slug'] ?? null) ?: $validated['name'],
            $item->id
        );

        $imagePath = $item->image_path;
        if ($request->hasFile('image')) {
            $imagePath = $this->storePublicImage($request->file('image'), 'items', $item->image_path);
        }

        $item->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'type' => $validated['type'],
            'total_stock' => $validated['total_stock'],
            'daily_rate' => $validated['daily_rate'],
            'image_path' => $imagePath,
        ]);

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $this->deletePublicImage($item->image_path);
        $item->delete();

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
