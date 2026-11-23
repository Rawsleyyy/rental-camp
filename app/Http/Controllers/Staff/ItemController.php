<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(): View
    {
        $items = Item::with('category')->orderByDesc('id')->paginate(12);

        return view('staff.items.index', compact('items'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('staff.items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:items,slug'],
            'description' => ['required', 'string'],
            'is_package' => ['nullable', 'boolean'],
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

        $item = Item::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'type' => 'equipment',
            'is_package' => $request->boolean('is_package'),
            'total_stock' => $validated['total_stock'],
            'daily_rate' => $validated['daily_rate'],
            'image_path' => $imagePath,
        ]);

        ActivityLogger::log(
            'CREATE_ITEM',
            "Menambahkan alat camping baru: {$item->name} (Stok: {$item->total_stock}, Tarif: Rp " . number_format($item->daily_rate, 0, ',', '.') . "/hari)."
        );

        return redirect()
            ->route('staff.items.index')
            ->with('success', "Alat {$item->name} berhasil ditambahkan.");
    }

    public function edit(Item $item): View
    {
        $categories = Category::orderBy('name')->get();

        return view('staff.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('items', 'slug')->ignore($item->id)],
            'description' => ['required', 'string'],
            'is_package' => ['nullable', 'boolean'],
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
            'type' => 'equipment',
            'is_package' => $request->boolean('is_package'),
            'total_stock' => $validated['total_stock'],
            'daily_rate' => $validated['daily_rate'],
            'image_path' => $imagePath,
        ]);

        ActivityLogger::log(
            'UPDATE_ITEM',
            "Memperbarui data alat: {$item->name} (Stok: {$item->total_stock}, Tarif: Rp " . number_format($item->daily_rate, 0, ',', '.') . "/hari)."
        );

        return redirect()
            ->route('staff.items.index')
            ->with('success', "Data alat {$item->name} berhasil diperbarui.");
    }

    public function destroy(Item $item): RedirectResponse
    {
        $name = $item->name;
        $this->deletePublicImage($item->image_path);
        $item->delete();

        ActivityLogger::log('DELETE_ITEM', "Menghapus data alat camping: {$name}.");

        return redirect()
            ->route('staff.items.index')
            ->with('success', "Alat {$name} berhasil dihapus.");
    }
}
