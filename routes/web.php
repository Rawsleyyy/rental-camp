<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Staff\ArticleController as StaffArticleController;
use App\Http\Controllers\Staff\ItemController as StaffItemController;
use App\Http\Controllers\Staff\StaffBookingController;
use App\Http\Controllers\Staff\StaffCancellationController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\UserController;
use App\Models\Cart;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Storefront Routes (No login required)
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/katalog', [PublicController::class, 'catalog'])->name('katalog');
Route::get('/artikel', [PublicController::class, 'articles'])->name('artikel.index');
Route::get('/artikel/{slug}', [PublicController::class, 'articleDetail'])->name('artikel.show');
Route::match(['get', 'post'], '/cek-booking', [PublicController::class, 'checkBooking'])->name('cek-booking');
Route::get('/statistik', [PublicController::class, 'statistics'])->name('statistik');

// Quick Rent Redirect (Requires login as role:user -> adds item to cart -> goes to cart/checkout)
Route::get('/sewa/{item?}', function (?string $item = null) {
    $user = Auth::user();

    if ($item) {
        $model = Item::where('slug', $item)->first();
        if ($model && $model->total_stock > 0) {
            $cart = Cart::firstOrNew([
                'user_id' => $user->id,
                'item_id' => $model->id,
            ]);
            $cart->quantity = min($model->total_stock, ($cart->exists ? $cart->quantity : 0) + 1);
            $cart->save();
        }
    }

    return redirect()->route('cart.index');
})->middleware(['auth', 'role:user'])->name('sewa.create');

// Role-aware Dashboard Redirect (User goes to /profile, Admin/Staff go to their workspaces)
Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user?->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'staff' => redirect()->route('staff.dashboard'),
        default => redirect()->route('profile.edit'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// User-Only Rental Routes (Cart, Checkout, Payment, Cancellation)
Route::middleware(['auth', 'role:user'])->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout & Real-Time Stock Validation
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/check-stock', [CheckoutController::class, 'checkStock'])->name('checkout.check-stock');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // User Booking Payment & Cancellation Actions
    Route::post('/bookings/{booking}/pay', [UserBookingController::class, 'pay'])->name('bookings.pay');
    Route::post('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');
});

// Profile & Activity Hub (accessible to all authenticated roles)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
        Route::get('/damage-reports', [AdminController::class, 'damageReports'])->name('damage-reports.index');
        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs.index');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('items', ItemController::class)->except(['show']);
        Route::resource('articles', ArticleController::class)->except(['show']);
    });

// Staff Routes (Accessible by Staff and Admin)
Route::middleware(['auth', 'role:staff,admin'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
        Route::get('/approvals', fn () => redirect()->route('staff.bookings.index', ['status' => 'pending']))->name('approvals');

        // Basecamp QR Scanner
        Route::match(['get', 'post'], '/scan', [StaffBookingController::class, 'scan'])->name('scan');

        // Bookings & Transaction Lifecycle
        Route::get('/bookings', [StaffBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [StaffBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/approve', [StaffBookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/bookings/{booking}/reject', [StaffBookingController::class, 'reject'])->name('bookings.reject');
        Route::post('/bookings/{booking}/verify-payment', [StaffBookingController::class, 'verifyPayment'])->name('bookings.verify-payment');
        Route::post('/bookings/{booking}/reject-payment', [StaffBookingController::class, 'rejectPayment'])->name('bookings.reject-payment');
        Route::post('/bookings/{booking}/checkout', [StaffBookingController::class, 'checkoutGear'])->name('bookings.checkout');
        Route::post('/bookings/{booking}/return', [StaffBookingController::class, 'processReturn'])->name('bookings.return');

        // Returns Status & Overdue Report
        Route::get('/returns', [StaffController::class, 'returns'])->name('returns');

        // Cancellations Approval
        Route::get('/cancellations', [StaffCancellationController::class, 'index'])->name('cancellations.index');
        Route::post('/cancellations/{cancellation}/approve', [StaffCancellationController::class, 'approve'])->name('cancellations.approve');
        Route::post('/cancellations/{cancellation}/reject', [StaffCancellationController::class, 'reject'])->name('cancellations.reject');

        // Staff CRUD for Camping Items & Articles
        Route::resource('items', StaffItemController::class)->except(['show']);
        Route::resource('articles', StaffArticleController::class)->except(['show']);
    });

// Legacy User Routes (redirect cleanly to /profile)
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/rentals', [UserController::class, 'rentals'])->name('rentals');
    });

require __DIR__.'/auth.php';
