<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cancellation;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StaffCancellationController extends Controller
{
    /**
     * List all cancellation requests ordered by pending status first.
     */
    public function index(): View
    {
        $cancellations = Cancellation::with(['booking.user', 'booking.bookingItems.item', 'user'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('staff.cancellations.index', compact('cancellations'));
    }

    /**
     * Approve a pending cancellation request and cancel its booking.
     */
    public function approve(Request $request, Cancellation $cancellation): RedirectResponse
    {
        if ($cancellation->status !== 'pending') {
            return back()->with('error', 'Pengajuan pembatalan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'refund_amount' => ['nullable', 'integer', 'min:0'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking = $cancellation->booking;
        $refundAmount = (int) ($validated['refund_amount'] ?? 0);

        DB::transaction(function () use ($cancellation, $booking, $refundAmount, $validated) {
            $cancellation->update([
                'status' => 'approved',
                'refund_amount' => $refundAmount,
                'admin_notes' => $validated['admin_notes'] ?? 'Pembatalan disetujui oleh Staff.',
            ]);

            if ($booking) {
                $booking->update([
                    'status' => 'cancelled',
                ]);
            }
        });

        $code = $booking?->booking_code ?? "#{$cancellation->booking_id}";
        ActivityLogger::log(
            'APPROVE_CANCELLATION',
            "Menyetujui pembatalan booking {$code} dengan refund Rp " . number_format($refundAmount, 0, ',', '.') . "."
        );

        return back()->with('success', "Pembatalan booking {$code} telah disetujui.");
    }

    /**
     * Reject a pending cancellation request.
     */
    public function reject(Request $request, Cancellation $cancellation): RedirectResponse
    {
        if ($cancellation->status !== 'pending') {
            return back()->with('error', 'Pengajuan pembatalan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'admin_notes.required' => 'Catatan alasan penolakan pembatalan wajib diisi.',
        ]);

        $cancellation->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        $code = $cancellation->booking?->booking_code ?? "#{$cancellation->booking_id}";
        ActivityLogger::log(
            'REJECT_CANCELLATION',
            "Menolak pengajuan pembatalan booking {$code}. Catatan: {$validated['admin_notes']}"
        );

        return back()->with('success', "Pengajuan pembatalan booking {$code} ditolak.");
    }
}
