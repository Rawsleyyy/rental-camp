<?php

namespace App\Services;

use App\Models\BookingItem;
use App\Models\Item;
use App\Models\User;

class StockService
{
    /**
     * Statuses that actively hold inventory across overlapping rental dates.
     *
     * @var array<int, string>
     */
    public const BLOCKING_STATUSES = [
        'pending',
        'approved',
        'confirmed',
        'ongoing',
    ];

    /**
     * Compute available stock for an item across an inclusive [startDate, endDate] range.
     */
    public function getAvailableStock(int $itemId, string $startDate, string $endDate): int
    {
        $item = Item::findOrFail($itemId);

        $bookedQuantity = (int) BookingItem::query()
            ->where('item_id', $itemId)
            ->whereHas('booking', function ($query) use ($startDate, $endDate) {
                $query->whereIn('status', self::BLOCKING_STATUSES)
                    ->whereDate('start_date', '<=', $endDate)
                    ->whereDate('end_date', '>=', $startDate);
            })
            ->sum('quantity');

        return max(0, (int) $item->total_stock - $bookedQuantity);
    }

    /**
     * Check stock availability for every item in the user's cart on [startDate, endDate].
     *
     * @return array{
     *     all_sufficient: bool,
     *     items: array<int, array{
     *         cart_id: int,
     *         item_id: int,
     *         name: string,
     *         daily_rate: int,
     *         requested: int,
     *         available: int,
     *         is_sufficient: bool
     *     }>
     * }
     */
    public function checkCartAvailability(User $user, string $startDate, string $endDate): array
    {
        $carts = $user->carts()->with('item')->get();
        $results = [];
        $allSufficient = true;

        foreach ($carts as $cart) {
            if (! $cart->item) {
                continue;
            }

            $available = $this->getAvailableStock($cart->item_id, $startDate, $endDate);
            $isSufficient = $available >= $cart->quantity;

            if (! $isSufficient) {
                $allSufficient = false;
            }

            $results[] = [
                'cart_id' => $cart->id,
                'item_id' => $cart->item_id,
                'name' => $cart->item->name,
                'daily_rate' => (int) $cart->item->daily_rate,
                'requested' => (int) $cart->quantity,
                'available' => $available,
                'is_sufficient' => $isSufficient,
            ];
        }

        return [
            'all_sufficient' => $allSufficient,
            'items' => $results,
        ];
    }
}
