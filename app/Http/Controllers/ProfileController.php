<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Cancellation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile & rental activity hub.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $bookings = $user->bookings()
            ->with(['bookingItems.item', 'cancellations', 'damageReports'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $cancellations = Cancellation::query()
            ->with('booking')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('booking', fn ($bookingQuery) => $bookingQuery->where('user_id', $user->id));
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $cancellableBookings = $bookings->filter(function ($booking) {
            if (! in_array($booking->status, ['pending', 'approved', 'waiting_verification', 'confirmed'], true)) {
                return false;
            }

            return ! $booking->cancellations->whereIn('status', ['pending', 'approved'])->isNotEmpty();
        });

        $requestedTab = $request->query('tab') ?: session('active_tab');
        $activeTab = in_array($requestedTab, ['profile', 'rentals', 'cancellations'], true)
            ? $requestedTab
            : 'profile';

        return view('profile.edit', [
            'user' => $user,
            'bookings' => $bookings,
            'cancellations' => $cancellations,
            'cancellableBookings' => $cancellableBookings,
            'activeTab' => $activeTab,
        ]);
    }

    /**
     * Update the user's profile information (including avatar, phone, address).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $user->phone,
            'address' => $validated['address'] ?? $user->address,
        ]);

        if ($request->hasFile('avatar')) {
            $user->avatar = $this->storePublicImage($request->file('avatar'), 'avatars', $user->avatar);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $this->deletePublicImage($user->avatar);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
