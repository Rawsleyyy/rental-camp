<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the authenticated user's rental cart.
     */
    public function index(): View
    {
        $carts = Auth::user()
            ->carts()
            ->with('item.category')
            ->latest()
            ->get();

        $dailySubtotal = $carts->sum(fn (Cart $cart) => (int) ($cart->item?->daily_rate ?? 0) * $cart->quantity);

        return view('cart.index', compact('carts', 'dailySubtotal'));
    }

    /**
     * Add an item to the authenticated user's cart.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'buy_now' => ['nullable', 'boolean'],
        ]);

        $item = Item::findOrFail($validated['item_id']);
        $requestedQty = (int) ($validated['quantity'] ?? 1);

        if ($item->total_stock < 1) {
            return back()->with('error', "Maaf, stok {$item->name} sedang kosong.");
        }

        $cart = Cart::firstOrNew([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
        ]);

        $newQuantity = ($cart->exists ? $cart->quantity : 0) + $requestedQty;
        if ($newQuantity > $item->total_stock) {
            $newQuantity = $item->total_stock;
        }

        $cart->quantity = $newQuantity;
        $cart->save();

        if ($request->boolean('buy_now')) {
            return redirect()
                ->route('checkout.index')
                ->with('success', "{$item->name} ditambahkan ke keranjang. Lanjutkan menentukan tanggal sewa.");
        }

        return redirect()
            ->route('cart.index')
            ->with('success', "{$item->name} berhasil ditambahkan ke keranjang sewa.");
    }

    /**
     * Update item quantity in cart.
     */
    public function update(Request $request, Cart $cart): RedirectResponse
    {
        abort_if($cart->user_id !== Auth::id(), 403);

        $maxStock = max(1, (int) ($cart->item?->total_stock ?? 1));

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', "max:{$maxStock}"],
        ]);

        $cart->update([
            'quantity' => (int) $validated['quantity'],
        ]);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Jumlah unit pada keranjang berhasil diperbarui.');
    }

    /**
     * Remove an item from cart.
     */
    public function destroy(Cart $cart): RedirectResponse
    {
        abort_if($cart->user_id !== Auth::id(), 403);

        $itemName = $cart->item?->name ?? 'Item';
        $cart->delete();

        return redirect()
            ->route('cart.index')
            ->with('success', "{$itemName} dihapus dari keranjang.");
    }
}
