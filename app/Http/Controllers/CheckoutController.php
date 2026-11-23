<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Cart;
use App\Models\Item;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout / rental request page.
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();
        $carts = $user->carts()->with('item.category')->get();

        if ($carts->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang sewa Anda masih kosong. Silakan pilih alat terlebih dahulu.');
        }

        $dailySubtotal = $carts->sum(fn (Cart $cart) => (int) ($cart->item?->daily_rate ?? 0) * $cart->quantity);

        return view('checkout.index', compact('carts', 'user', 'dailySubtotal'));
    }

    /**
     * Real-time AJAX endpoint checking stock availability across the chosen date range.
     */
    public function checkStock(Request $request, StockService $stockService): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $availability = $stockService->checkCartAvailability($request->user(), $startDate, $endDate);

        $totalPrice = collect($availability['items'])->sum(function (array $entry) use ($totalDays) {
            return $entry['daily_rate'] * $entry['requested'] * $totalDays;
        });

        return response()->json([
            'total_days' => $totalDays,
            'total_price' => $totalPrice,
            'all_sufficient' => $availability['all_sufficient'],
            'items' => $availability['items'],
        ]);
    }

    /**
     * Submit checkout inside a DB transaction with strict date-overlap stock validation.
     */
    public function store(Request $request, StockService $stockService): RedirectResponse
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:30'],
        ]);

        $user = $request->user();
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

        $booking = DB::transaction(function () use ($user, $validated, $startDate, $endDate, $totalDays, $stockService) {
            $carts = $user->carts()->with('item')->get();

            if ($carts->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang sewa Anda kosong. Tambahkan alat terlebih dahulu.',
                ]);
            }

            // Lock items to prevent concurrent race conditions during checkout
            $lockedItems = Item::query()
                ->whereIn('id', $carts->pluck('item_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $stockErrors = [];
            $totalPrice = 0;

            foreach ($carts as $cart) {
                $item = $lockedItems->get($cart->item_id);

                if (! $item) {
                    $stockErrors["item_{$cart->item_id}"] = 'Salah satu item di keranjang sudah tidak tersedia.';
                    continue;
                }

                $availableStock = $stockService->getAvailableStock($item->id, $startDate, $endDate);

                if ($availableStock < $cart->quantity) {
                    $stockErrors["item_{$item->id}"] = "Stok {$item->name} tidak mencukupi pada tanggal {$startDate} s/d {$endDate} (tersedia: {$availableStock} unit, diminta: {$cart->quantity} unit).";
                    continue;
                }

                $totalPrice += (int) $item->daily_rate * (int) $cart->quantity * $totalDays;
            }

            if ($stockErrors !== []) {
                throw ValidationException::withMessages($stockErrors);
            }

            $bookingCode = $this->generateUniqueBookingCode();

            $booking = Booking::create([
                'user_id' => $user->id,
                'booking_code' => $bookingCode,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_days' => $totalDays,
                'contact_name' => $validated['contact_name'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'is_insured' => false,
            ]);

            foreach ($carts as $cart) {
                $item = $lockedItems->get($cart->item_id);
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'item_id' => $item->id,
                    'quantity' => $cart->quantity,
                    'price_at_booking' => $item->daily_rate,
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            return $booking;
        });

        return redirect()
            ->route('profile.edit', ['tab' => 'rentals'])
            ->with('status', 'booking-created')
            ->with('booking_code', $booking->booking_code)
            ->with('success', "Pengajuan sewa {$booking->booking_code} berhasil dibuat dan sedang menunggu ACC Staff.");
    }

    private function generateUniqueBookingCode(): string
    {
        $prefix = 'RWL-' . now()->format('Ymd') . '-';

        do {
            $code = $prefix . str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
            if (Booking::where('booking_code', $code)->exists()) {
                $code = $prefix . strtoupper(Str::random(4));
            }
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}
