<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cancellation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserBookingController extends Controller
{
    /**
     * Submit realistic payment proof with unique code & sender name for Staff verification.
     */
    public function pay(Request $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if ($booking->status !== 'approved') {
            return redirect()
                ->route('profile.edit', ['tab' => 'rentals'])
                ->with('error', 'Pembayaran hanya dapat dikirim untuk booking berstatus Disetujui (Approved).');
        }

        $validator = Validator::make($request->all(), [
            'payment_method' => ['required', 'in:Transfer Bank,QRIS'],
            'payment_sender_name' => ['required', 'string', 'max:255'],
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'payment_method.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'payment_sender_name.required' => 'Nama pengirim rekening/e-wallet wajib diisi.',
            'payment_proof.required' => 'Bukti transfer/pembayaran wajib diunggah.',
            'payment_proof.image' => 'Bukti pembayaran harus berupa file gambar.',
            'payment_proof.mimes' => 'Bukti pembayaran harus berformat JPG, JPEG, atau PNG.',
            'payment_proof.max' => 'Ukuran file bukti pembayaran maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile.edit', ['tab' => 'rentals'])
                ->withErrors($validator)
                ->withInput()
                ->with('pay_booking_id', $booking->id)
                ->with('error', 'Pengiriman bukti pembayaran gagal. Periksa kembali isian formulir Anda.');
        }

        $validated = $validator->validated();
        $proofPath = $this->storePublicImage($request->file('payment_proof'), 'payments', $booking->payment_proof);

        $uniqueCode = $booking->unique_code ?: random_int(100, 999);

        $booking->update([
            'status' => 'waiting_verification',
            'unique_code' => $uniqueCode,
            'payment_method' => $validated['payment_method'],
            'payment_sender_name' => $validated['payment_sender_name'],
            'payment_proof' => $proofPath,
            'payment_rejection_reason' => null,
        ]);

        return redirect()
            ->route('profile.edit', ['tab' => 'rentals'])
            ->with('status', 'payment-submitted')
            ->with('booking_code', $booking->booking_code);
    }

    /**
     * Submit a cancellation request for an eligible booking.
     */
    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if (! in_array($booking->status, ['pending', 'approved', 'waiting_verification', 'confirmed'], true)) {
            return redirect()
                ->route('profile.edit', ['tab' => 'cancellations'])
                ->with('error', 'Booking ini tidak dapat dibatalkan karena alat sudah diambil atau transaksi telah selesai.');
        }

        $hasActiveCancellation = $booking->cancellations()
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasActiveCancellation) {
            return redirect()
                ->route('profile.edit', ['tab' => 'cancellations'])
                ->with('error', 'Pengajuan pembatalan untuk kode booking ini sudah pernah dikirim.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'reason.required' => 'Alasan pembatalan wajib diisi.',
            'reason.min' => 'Alasan pembatalan minimal 5 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile.edit', ['tab' => 'cancellations'])
                ->withErrors($validator)
                ->withInput();
        }

        Cancellation::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'reason' => $validator->validated()['reason'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('profile.edit', ['tab' => 'cancellations'])
            ->with('status', 'cancellation-submitted')
            ->with('booking_code', $booking->booking_code);
    }
}
