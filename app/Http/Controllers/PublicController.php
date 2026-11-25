<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Cancellation;
use App\Models\Category;
use App\Models\Item;
use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Public Homepage (/).
     */
    public function home(): View
    {
        $featuredEquipments = Item::with('category')
            ->where('is_package', false)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $featuredPackages = Item::with('category')
            ->where('is_package', true)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $latestArticles = Article::with('user')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        $heroShowcaseItems = Item::with('category')
            ->where('is_package', false)
            ->whereNotNull('image_path')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('welcome', compact('featuredEquipments', 'featuredPackages', 'latestArticles', 'heroShowcaseItems'));
    }

    /**
     * Public Catalog (/katalog) with search, category, and package filters.
     */
    public function catalog(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $categorySlug = trim((string) $request->query('category', ''));
        $typeFilter = trim((string) $request->query('type', 'all'));

        $itemsQuery = Item::with('category')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($search !== '') {
            $itemsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categorySlug !== '') {
            $itemsQuery->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }

        if ($typeFilter === 'satuan') {
            $itemsQuery->where('is_package', false);
        } elseif ($typeFilter === 'paket') {
            $itemsQuery->where('is_package', true);
        }

        $items = $itemsQuery->paginate(12)->withQueryString();
        $categories = Category::withCount('items')->orderBy('name')->get();

        return view('katalog', compact('items', 'categories', 'search', 'categorySlug', 'typeFilter'));
    }

    /**
     * Public Article List (/artikel).
     */
    public function articles(): View
    {
        $articles = Article::with('user')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(9);

        return view('artikel.index', compact('articles'));
    }

    /**
     * Public Article Detail (/artikel/{slug}).
     */
    public function articleDetail(string $slug): View
    {
        $article = Article::with('user')->where('slug', $slug)->firstOrFail();

        $relatedArticles = Article::with('user')
            ->where('id', '!=', $article->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        return view('artikel.show', compact('article', 'relatedArticles'));
    }

    /**
     * Public Booking Verification & Pickup Card (/cek-booking).
     */
    public function checkBooking(Request $request, QrCodeService $qrCodeService): View
    {
        $booking = null;
        $qrSvg = null;
        $searched = false;
        $notFound = false;

        $bookingCode = trim((string) $request->input('booking_code', ''));
        $contact = trim((string) $request->input('contact', ''));

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'booking_code' => ['required', 'string', 'max:100'],
                'contact' => ['required', 'string', 'max:255'],
            ]);

            $bookingCode = trim($validated['booking_code']);
            $contact = trim($validated['contact']);
        }

        if ($bookingCode !== '' && $contact !== '') {
            $searched = true;

            $booking = Booking::with(['user', 'handoverStaff', 'bookingItems.item'])
                ->where('booking_code', $bookingCode)
                ->where(function ($query) use ($contact) {
                    $query->where('contact_email', $contact)
                        ->orWhere('contact_phone', $contact)
                        ->orWhereHas('user', function ($userQuery) use ($contact) {
                            $userQuery->where('email', $contact)
                                ->orWhere('phone', $contact);
                        });
                })
                ->first();

            if (! $booking) {
                $notFound = true;
            } elseif (in_array($booking->status, ['confirmed', 'ongoing'], true)) {
                $qrSvg = $qrCodeService->toSvg($booking->booking_code, 180);
            }
        }

        return view('cek-booking', compact('booking', 'qrSvg', 'searched', 'notFound', 'bookingCode', 'contact'));
    }

    /**
     * Public Rental Statistics Page (/statistik) powered by Chart.js and real DB data.
     */
    public function statistics(): View
    {
        // 1. Monthly rental counts for the last 6 months
        $months = collect(range(5, 0))->map(fn (int $offset) => now()->startOfMonth()->subMonths($offset));
        $startWindow = $months->first()->copy()->startOfMonth();

        $bookingsInWindow = Booking::query()
            ->where('start_date', '>=', $startWindow)
            ->get(['id', 'start_date', 'status']);

        $monthlyLabels = [];
        $monthlyCounts = [];

        foreach ($months as $monthDate) {
            $monthKey = $monthDate->format('Y-m');
            $monthlyLabels[] = $monthDate->translatedFormat('M Y');
            $monthlyCounts[] = $bookingsInWindow->filter(function ($booking) use ($monthKey) {
                return Carbon::parse($booking->start_date)->format('Y-m') === $monthKey;
            })->count();
        }

        // 2. Booking status & cancellation comparison
        $statusMap = [
            'pending' => 'Menunggu ACC',
            'approved' => 'Disetujui (Belum Bayar)',
            'confirmed' => 'Terkonfirmasi',
            'ongoing' => 'Sedang Berjalan',
            'returned' => 'Selesai / Kembali',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak',
        ];

        $rawStatusCounts = Booking::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusLabels = array_values($statusMap);
        $statusCounts = collect(array_keys($statusMap))
            ->map(fn (string $key) => (int) ($rawStatusCounts[$key] ?? 0))
            ->all();

        // 3. Item stock usage / utilization rate
        $itemsWithUsage = Item::query()
            ->withSum(['bookingItems as total_rented' => function ($query) {
                $query->whereHas('booking', function ($bookingQuery) {
                    $bookingQuery->whereNotIn('status', ['rejected', 'cancelled']);
                });
            }], 'quantity')
            ->orderByDesc('total_rented')
            ->take(8)
            ->get();

        $itemLabels = $itemsWithUsage->pluck('name')->all();
        $itemRentedCounts = $itemsWithUsage->map(fn ($item) => (int) ($item->total_rented ?? 0))->all();
        $itemUtilizationRates = $itemsWithUsage->map(function ($item) {
            $stock = max(1, (int) $item->total_stock);
            $rented = (int) ($item->total_rented ?? 0);

            return round(min(100, ($rented / $stock) * 100), 1);
        })->all();

        $summary = [
            'totalBookings' => Booking::count(),
            'totalCancellations' => Cancellation::count(),
            'totalItemsStock' => (int) Item::sum('total_stock'),
            'totalRentedUnits' => (int) BookingItem::whereHas('booking', fn ($q) => $q->whereNotIn('status', ['rejected', 'cancelled']))->sum('quantity'),
        ];

        return view('statistik', compact(
            'monthlyLabels',
            'monthlyCounts',
            'statusLabels',
            'statusCounts',
            'itemLabels',
            'itemRentedCounts',
            'itemUtilizationRates',
            'summary'
        ));
    }
}
