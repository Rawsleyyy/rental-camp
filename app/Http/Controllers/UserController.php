<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Redirect user to the unified Profile & Rental Activity Hub.
     */
    public function dashboard(): RedirectResponse
    {
        return redirect()->route('profile.edit', ['tab' => 'rentals']);
    }

    /**
     * Redirect user to the rentals tab in Profile.
     */
    public function rentals(): RedirectResponse
    {
        return redirect()->route('profile.edit', ['tab' => 'rentals']);
    }
}
