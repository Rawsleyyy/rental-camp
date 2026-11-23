<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DamageReport;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StaffBookingController extends Controller
{
    /**
     * List bookings with status tabs and search filter.
     */
    public function index(Request $request): View
    {
        $status = trim((string) $request->query('status', 'all'));
        $search = trim((string) $request->query('q', ''));

        $counts = [
            'all' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'waiting_verification' => Booking::where('status', 'waiting_verification')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'ongoing' => Booking::where('status', 'ongoing')->count(),
            'returned' => Booking::where('status', 'returned')->count(),
            'closed' => Booking::whereIn('status', ['rejected', 'cancelled'])->count(),
        ];

        $query = Booking::with(['user', 'bookingItems.item'])
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($status === 'closed') {
            $query->whereIn('status', ['rejected', 'cancelled']);
        } elseif ($status !== 'all' && $status !== '') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $bookings = $query->paginate(12)->withQueryString();

        return view('staff.bookings.index', compact('bookings', 'status', 'search', 'counts'));
    }

    /**
     * Basecamp QR Scanner & Quick Booking Lookup (/staff/scan).
     */
    public function scan(Request $request): View|RedirectResponse
    {
        $rawCode = trim((string) $request->input('booking_code', ''));
        $code = ltrim($rawCode, '#');
        $notFoundCode = null;

        if ($code !== '') {
            $booking = Booking::where('booking_code', $code)->first();

            if ($booking) {
                return redirect()
                    ->route('staff.bookings.show', $booking)
                    ->with('success', "QR Code {$booking->booking_code} berhasil dipindai! Silakan cek daftar perlengkapan & proses transaksi.");
            }

            $notFoundCode = $code;
        }

        $readyBookings = Booking::with(['user', 'bookingItems.item'])
            ->where('status', 'confirmed')
            ->orderBy('start_date')
            ->take(10)
            ->get();

        $ongoingBookings = Booking::with(['user', 'bookingItems.item'])
            ->where('status', 'ongoing')
            ->orderBy('end_date')
            ->take(10)
            ->get();

        return view('staff.scan', compact('readyBookings', 'ongoingBookings', 'notFoundCode', 'code'));
    }

    /**
     * Display detailed view of a booking and Staff action panels.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'handoverStaff', 'bookingItems.item', 'cancellations', 'damageReports.item']);

        return view('staff.bookings.show', compact('booking'));
    }

    /**
     * Approve a pending booking and generate a 3-digit unique transfer code.
     */
    public function approve(Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking berstatus Pending yang dapat disetujui.');
        }

        $uniqueCode = random_int(100, 999);

        $booking->update([
            'status' => 'approved',
            'unique_code' => $uniqueCode,
            'rejection_reason' => null,
        ]);

        ActivityLogger::log(
            'ACC_BOOKING',
            "Menyetujui pengajuan sewa {$booking->booking_code} dengan kode unik {$uniqueCode}."
        );

        return back()->with('success', "Booking {$booking->booking_code} disetujui (ACC) dengan kode unik {$uniqueCode}.");
    }

    /**
     * Reject a pending booking with mandatory reason.
     */
    public function reject(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking berstatus Pending yang dapat ditolak.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        ActivityLogger::log(
            'REJECT_BOOKING',
            "Menolak pengajuan sewa {$booking->booking_code}. Alasan: {$validated['rejection_reason']}"
        );

        return back()->with('success', "Booking {$booking->booking_code} telah ditolak.");
    }

    /**
     * Verify uploaded payment proof as valid -> status becomes confirmed.
     */
    public function verifyPayment(Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'waiting_verification') {
            return back()->with('error', 'Booking ini tidak sedang menunggu verifikasi pembayaran.');
        }

        $booking->update([
            'status' => 'confirmed',
            'paid_at' => now(),
            'payment_rejection_reason' => null,
        ]);

        ActivityLogger::log(
            'VERIFY_PAYMENT',
            "Memverifikasi pembayaran booking {$booking->booking_code} (Pengirim: {$booking->payment_sender_name}, Metode: {$booking->payment_method})."
        );

        return back()->with('success', "Pembayaran {$booking->booking_code} dinyatakan valid. Status kini Confirmed.");
    }

    /**
     * Reject uploaded payment proof -> return status to approved so user can re-upload.
     */
    public function rejectPayment(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'waiting_verification') {
            return back()->with('error', 'Booking ini tidak sedang menunggu verifikasi pembayaran.');
        }

        $validated = $request->validate([
            'payment_rejection_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'payment_rejection_reason.required' => 'Alasan penolakan bukti pembayaran wajib diisi.',
        ]);

        $booking->update([
            'status' => 'approved',
            'payment_rejection_reason' => $validated['payment_rejection_reason'],
        ]);

        ActivityLogger::log(
            'REJECT_PAYMENT',
            "Menolak bukti pembayaran {$booking->booking_code}. Alasan: {$validated['payment_rejection_reason']}"
        );

        return back()->with('success', "Bukti pembayaran {$booking->booking_code} ditolak dan dikembalikan ke penyewa untuk upload ulang.");
    }

    /**
     * Check-out / hand over gear at basecamp -> status becomes ongoing & persists handover checklist.
     */
    public function checkoutGear(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Hanya booking berstatus Confirmed yang siap diserahkan.');
        }

        $booking->load('bookingItems');
        $now = now();

        if ($request->boolean('from_checklist')) {
            $validated = $request->validate([
                'checked_items' => ['required', 'array'],
                'checked_items.*' => ['integer'],
                'item_notes' => ['nullable', 'array'],
                'item_notes.*' => ['nullable', 'string', 'max:255'],
            ], [
                'checked_items.required' => 'Semua item pada checklist wajib dicentang sebelum diserahkan ke penyewa.',
            ]);

            $checkedIds = collect($validated['checked_items'])->map(fn ($id) => (int) $id)->all();
            $requiredIds = $booking->bookingItems->pluck('id')->all();
            $missingIds = array_diff($requiredIds, $checkedIds);

            if ($missingIds !== []) {
                return back()
                    ->withInput()
                    ->with('error', 'Harap centang semua item perlengkapan pada checklist serah terima sebelum menyerahkan alat.');
            }

            $itemNotes = $validated['item_notes'] ?? [];

            DB::transaction(function () use ($booking, $itemNotes, $now) {
                foreach ($booking->bookingItems as $bookingItem) {
                    $note = trim((string) ($itemNotes[$bookingItem->id] ?? ''));
                    $bookingItem->update([
                        'is_handover_checked' => true,
                        'handover_notes' => $note !== '' ? $note : 'Kondisi fisik, kelengkapan & kebersihan oke',
                        'handover_checked_at' => $now,
                    ]);
                }

                $booking->update([
                    'status' => 'ongoing',
                    'picked_up_at' => $now,
                    'handover_staff_id' => Auth::id(),
                ]);
            });
        } else {
            DB::transaction(function () use ($booking, $now) {
                foreach ($booking->bookingItems as $bookingItem) {
                    $bookingItem->update([
                        'is_handover_checked' => true,
                        'handover_notes' => $bookingItem->handover_notes ?: 'Dicek & diserahkan petugas basecamp',
                        'handover_checked_at' => $now,
                    ]);
                }

                $booking->update([
                    'status' => 'ongoing',
                    'picked_up_at' => $now,
                    'handover_staff_id' => Auth::id(),
                ]);
            });
        }

        $checkedCount = $booking->bookingItems->count();
        ActivityLogger::log(
            'CHECKOUT_GEAR',
            "Menyerahkan alat sewa di basecamp untuk booking {$booking->booking_code} ({$checkedCount} item terverifikasi checklist)."
        );

        return back()->with('success', "Alat untuk {$booking->booking_code} lolos checklist ({$checkedCount} item) dan resmi diserahkan ke penyewa.");
    }

    /**
     * Process return of ongoing rental with strict backend calculation for late fees & damage reports.
     */
    public function processReturn(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'ongoing') {
            return back()->with('error', 'Hanya booking berstatus Sedang Disewa (Ongoing) yang dapat diproses pengembaliannya.');
        }

        $validated = $request->validate([
            'returned_at' => ['required', 'date'],
            'return_condition' => ['required', 'in:baik,rusak_ringan,rusak_berat,hilang'],
            'damage_fee' => ['nullable', 'integer', 'min:0'],
            'damage_notes' => ['nullable', 'string', 'max:1000'],
            'damaged_item_id' => ['nullable', 'exists:items,id'],
        ]);

        $returnedDate = Carbon::parse($validated['returned_at'])->startOfDay();
        $endDate = $booking->end_date->copy()->startOfDay();

        $isLate = false;
        $lateDays = 0;
        $lateFee = 0;

        if ($returnedDate->gt($endDate)) {
            $isLate = true;
            $lateDays = (int) $endDate->diffInDays($returnedDate);
            $dailyAverage = (float) $booking->total_price / max(1, (int) $booking->total_days);
            $lateFee = (int) round($dailyAverage * $lateDays);
        }

        $damageFee = (int) ($validated['damage_fee'] ?? 0);
        $condition = $validated['return_condition'];
        $notes = trim((string) ($validated['damage_notes'] ?? ''));

        DB::transaction(function () use ($booking, $validated, $isLate, $lateDays, $lateFee, $damageFee, $condition, $notes) {
            $booking->update([
                'status' => 'returned',
                'returned_at' => Carbon::parse($validated['returned_at'])->setTimeFrom(now()),
                'return_condition' => $condition,
                'is_late' => $isLate,
                'late_days' => $lateDays,
                'late_fee' => $lateFee,
                'damage_fee' => $damageFee,
            ]);

            if ($damageFee > 0 || $condition !== 'baik') {
                $targetItemId = $validated['damaged_item_id']
                    ?? $booking->bookingItems()->value('item_id');

                if ($targetItemId) {
                    $conditionLabel = str_replace('_', ' ', ucfirst($condition));
                    $description = "Kondisi kembali: {$conditionLabel}." . ($notes !== '' ? " Catatan: {$notes}" : '');

                    DamageReport::create([
                        'booking_id' => $booking->id,
                        'item_id' => $targetItemId,
                        'description' => $description,
                        'penalty_fee' => $damageFee,
                        'is_paid' => false,
                    ]);
                }
            }
        });

        $totalPenalty = $lateFee + $damageFee;

        ActivityLogger::log(
            'RETURN_GEAR',
            "Memproses pengembalian {$booking->booking_code} (Kondisi: {$condition}, Terlambat: {$lateDays} hari, Total Denda: Rp " . number_format($totalPenalty, 0, ',', '.') . ")."
        );

        return redirect()
            ->route('staff.bookings.show', $booking)
            ->with('success', "Pengembalian {$booking->booking_code} berhasil dicatat. Total denda: Rp " . number_format($totalPenalty, 0, ',', '.') . ".");
    }
}
