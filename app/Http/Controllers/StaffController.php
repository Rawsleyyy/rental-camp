<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Display the Staff workspace dashboard with real-time operational metrics.
     */
    public function dashboard(): View
    {
        $today = today();

        $pendingCount = Booking::where('status', 'pending')->count();
        $waitingVerificationCount = Booking::where('status', 'waiting_verification')->count();
        $ongoingCount = Booking::where('status', 'ongoing')->count();
        $overdueCount = Booking::where('status', 'ongoing')
            ->whereDate('end_date', '<', $today)
            ->count();

        $ongoingBookings = Booking::with(['user', 'bookingItems.item'])
            ->where('status', 'ongoing')
            ->orderBy('end_date')
            ->get();

        return view('staff.dashboard', compact(
            'pendingCount',
            'waitingVerificationCount',
            'ongoingCount',
            'overdueCount',
            'ongoingBookings'
        ));
    }

    /**
     * Display overdue/active unreturned bookings and completed return history.
     */
    public function returns(): View
    {
        $ongoingBookings = Booking::with(['user', 'bookingItems.item'])
            ->where('status', 'ongoing')
            ->orderBy('end_date')
            ->get();

        $returnedBookings = Booking::with(['user', 'bookingItems.item', 'damageReports'])
            ->where('status', 'returned')
            ->orderByDesc('returned_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('staff.returns', compact('ongoingBookings', 'returnedBookings'));
    }
}
