<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\Booking;
use App\Models\Category;
use App\Models\DamageReport;
use App\Models\Item;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the Admin workspace dashboard.
     */
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'totalAvailableTools' => (int) Item::sum('total_stock'),
            'pendingApprovals' => Booking::where('status', 'pending')->count(),
            'activeRentals' => Booking::whereIn('status', ['confirmed', 'ongoing'])->count(),
            'damageReports' => DamageReport::count(),
            'categoryCount' => Category::count(),
            'itemCount' => Item::count(),
            'articleCount' => Article::count(),
            'userCount' => User::count(),
            'recentItems' => Item::with('category')->latest()->take(6)->get(),
        ]);
    }

    /**
     * Display user account management overview.
     */
    public function users(): View
    {
        return view('admin.users.index', [
            'users' => User::latest()->paginate(10),
        ]);
    }

    /**
     * Display rental and cancellation transactions.
     */
    public function bookings(): View
    {
        return view('admin.bookings.index', [
            'bookings' => Booking::with(['user', 'bookingItems.item'])->latest()->paginate(10),
        ]);
    }

    /**
     * Display equipment damage reports.
     */
    public function damageReports(): View
    {
        return view('admin.damage-reports.index', [
            'damageReports' => DamageReport::with(['booking.user', 'item'])->latest()->paginate(10),
        ]);
    }

    /**
     * Display staff and admin activity logs.
     */
    public function activityLogs(): View
    {
        return view('admin.activity-logs.index', [
            'activityLogs' => ActivityLog::with('user')->latest()->paginate(12),
        ]);
    }
}
