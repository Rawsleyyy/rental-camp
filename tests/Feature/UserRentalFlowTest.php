<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserRentalFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_visitor_pages_render_with_seeded_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Paket Sewa',
            'slug' => 'paket-sewa',
        ]);

        Item::create([
            'category_id' => $category->id,
            'name' => 'Tenda Ultralight 2P',
            'slug' => 'tenda-ultralight-2p',
            'description' => 'Tenda camping anti hujan.',
            'type' => 'equipment',
            'is_package' => false,
            'total_stock' => 5,
            'daily_rate' => 65000,
        ]);

        Item::create([
            'category_id' => $category->id,
            'name' => 'Paket Solo Hiker',
            'slug' => 'paket-solo-hiker',
            'description' => 'Paket lengkap pendakian solo.',
            'type' => 'equipment',
            'is_package' => true,
            'total_stock' => 3,
            'daily_rate' => 135000,
        ]);

        $article = Article::create([
            'user_id' => $admin->id,
            'title' => 'Tips Memilih Tenda untuk Pemula',
            'slug' => 'tips-memilih-tenda-untuk-pemula',
            'content' => 'Panduan memilih tenda camping anti hujan.',
        ]);

        $this->get('/')->assertOk()->assertSee('Tenda Ultralight 2P')->assertSee('Paket Solo Hiker');
        $this->get('/katalog')->assertOk()->assertSee('Tenda Ultralight 2P')->assertSee('Paket Solo Hiker');
        $this->get('/katalog?type=paket')->assertOk()->assertSee('Paket Solo Hiker')->assertDontSee('Tenda Ultralight 2P');
        $this->get('/artikel')->assertOk()->assertSee($article->title);
        $this->get("/artikel/{$article->slug}")->assertOk()->assertSee($article->content);
        $this->get('/cek-booking')->assertOk();
        $this->get('/statistik')->assertOk();
    }

    public function test_guest_is_redirected_to_login_for_cart_and_checkout(): void
    {
        $category = Category::create(['name' => 'Tenda', 'slug' => 'tenda']);
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Tenda 4P',
            'slug' => 'tenda-4p',
            'description' => 'Tenda 4 orang tahan hujan.',
            'type' => 'equipment',
            'is_package' => false,
            'total_stock' => 4,
            'daily_rate' => 80000,
        ]);

        $this->get('/cart')->assertRedirect('/login');
        $this->post('/cart', ['item_id' => $item->id, 'quantity' => 1])->assertRedirect('/login');
        $this->get('/checkout')->assertRedirect('/login');
    }

    public function test_cart_ajax_stock_check_and_strict_overlap_checkout(): void
    {
        $user = User::factory()->create(['role' => 'user', 'phone' => '08111111111']);
        $otherUser = User::factory()->create(['role' => 'user']);
        $category = Category::create(['name' => 'Tenda', 'slug' => 'tenda']);

        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Tenda Dome 4P',
            'slug' => 'tenda-dome-4p',
            'description' => 'Tenda dome anti angin.',
            'type' => 'equipment',
            'is_package' => false,
            'total_stock' => 3,
            'daily_rate' => 100000,
        ]);

        $start = now()->addDays(5)->toDateString();
        $end = now()->addDays(7)->toDateString();

        // Create an existing blocking booking using 2 of 3 units on overlapping dates
        $existingBooking = Booking::create([
            'user_id' => $otherUser->id,
            'booking_code' => 'RWL-TEST-0001',
            'start_date' => now()->addDays(4)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'total_days' => 3,
            'contact_name' => $otherUser->name,
            'contact_email' => $otherUser->email,
            'contact_phone' => '08222222222',
            'total_price' => 600000,
            'status' => 'confirmed',
        ]);

        BookingItem::create([
            'booking_id' => $existingBooking->id,
            'item_id' => $item->id,
            'quantity' => 2,
            'price_at_booking' => 100000,
        ]);

        $stockService = app(StockService::class);
        $this->assertSame(1, $stockService->getAvailableStock($item->id, $start, $end));

        // Add 2 units to cart (more than available 1 unit for those overlapping dates)
        $this->actingAs($user)
            ->post('/cart', ['item_id' => $item->id, 'quantity' => 2])
            ->assertRedirect('/cart');

        $cart = Cart::where('user_id', $user->id)->where('item_id', $item->id)->firstOrFail();
        $this->assertSame(2, $cart->quantity);

        // AJAX stock check detects insufficient stock for 2 units on overlapping dates
        $this->actingAs($user)
            ->getJson('/checkout/check-stock?' . http_build_query([
                'start_date' => $start,
                'end_date' => $end,
            ]))
            ->assertOk()
            ->assertJson([
                'total_days' => 3,
                'all_sufficient' => false,
            ]);

        // Submitting checkout with 2 units must fail validation
        $this->actingAs($user)
            ->post('/checkout', [
                'start_date' => $start,
                'end_date' => $end,
                'contact_name' => $user->name,
                'contact_email' => $user->email,
                'contact_phone' => '08111111111',
            ])
            ->assertSessionHasErrors("item_{$item->id}");

        // Update cart quantity to 1 unit (matches available stock)
        $this->actingAs($user)
            ->patch("/cart/{$cart->id}", ['quantity' => 1])
            ->assertRedirect('/cart');

        $this->actingAs($user)
            ->post('/checkout', [
                'start_date' => $start,
                'end_date' => $end,
                'contact_name' => $user->name,
                'contact_email' => $user->email,
                'contact_phone' => '08111111111',
            ])
            ->assertRedirect(route('profile.edit', ['tab' => 'rentals']));

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'status' => 'pending',
            'total_days' => 3,
            'total_price' => 300000,
        ]);

        // Cart is cleared after checkout
        $this->assertSame(0, Cart::where('user_id', $user->id)->count());
    }

    public function test_user_payment_cancellation_and_cek_booking_pickup_card(): void
    {
        Storage::fake('public');

        $staff = User::factory()->create(['role' => 'staff']);
        $user = User::factory()->create([
            'role' => 'user',
            'phone' => '081299988877',
        ]);
        $category = Category::create(['name' => 'Carrier', 'slug' => 'carrier']);
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Carrier 60L',
            'slug' => 'carrier-60l',
            'description' => 'Tas gunung 60 liter.',
            'type' => 'equipment',
            'is_package' => false,
            'total_stock' => 5,
            'daily_rate' => 50000,
        ]);

        $approvedBooking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'RWL-20261122-1234',
            'start_date' => now()->addDays(2)->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(),
            'total_days' => 3,
            'contact_name' => $user->name,
            'contact_email' => $user->email,
            'contact_phone' => $user->phone,
            'total_price' => 150000,
            'unique_code' => 347,
            'status' => 'approved',
        ]);

        BookingItem::create([
            'booking_id' => $approvedBooking->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'price_at_booking' => 50000,
        ]);

        // Before payment submission: /cek-booking shows current status, not pickup QR card
        $this->post('/cek-booking', [
            'booking_code' => 'RWL-20261122-1234',
            'contact' => $user->email,
        ])->assertOk()
            ->assertSee('RWL-20261122-1234')
            ->assertSee('Disetujui')
            ->assertDontSee('Checklist Serah Terima Alat');

        // User submits realistic payment proof -> status becomes waiting_verification
        $proof = UploadedFile::fake()->image('bukti.jpg', 400, 600);
        $this->actingAs($user)
            ->post(route('bookings.pay', $approvedBooking), [
                'payment_method' => 'QRIS',
                'payment_sender_name' => 'Raka Petualang',
                'payment_proof' => $proof,
            ])
            ->assertRedirect(route('profile.edit', ['tab' => 'rentals']));

        $approvedBooking->refresh();
        $this->assertSame('waiting_verification', $approvedBooking->status);
        $this->assertSame('QRIS', $approvedBooking->payment_method);
        $this->assertSame('Raka Petualang', $approvedBooking->payment_sender_name);
        $this->assertNotNull($approvedBooking->payment_proof);
        Storage::disk('public')->assertExists($approvedBooking->payment_proof);
        $this->assertNull($approvedBooking->paid_at);

        // During waiting_verification: /cek-booking explains payment is being verified by Staff (no QR Pickup Card yet)
        $this->post('/cek-booking', [
            'booking_code' => 'RWL-20261122-1234',
            'contact' => $user->email,
        ])->assertOk()
            ->assertSee('Bukti pembayaran sedang diverifikasi oleh Staff')
            ->assertDontSee('Checklist Serah Terima Alat');

        // Staff verifies payment -> status becomes confirmed
        $this->actingAs($staff)
            ->post(route('staff.bookings.verify-payment', $approvedBooking))
            ->assertRedirect();

        $approvedBooking->refresh();
        $this->assertSame('confirmed', $approvedBooking->status);
        $this->assertNotNull($approvedBooking->paid_at);

        // After confirmation: /cek-booking displays Pickup Card with SVG QR Code
        $this->post('/cek-booking', [
            'booking_code' => 'RWL-20261122-1234',
            'contact' => $user->phone,
        ])->assertOk()
            ->assertSee('Kartu Pengambilan Alat', false)
            ->assertSee('Carrier 60L')
            ->assertSee('<svg', false);

        // User submits cancellation request
        $this->actingAs($user)
            ->post(route('bookings.cancel', $approvedBooking), [
                'reason' => 'Perubahan jadwal keberangkatan mendadak.',
            ])
            ->assertRedirect(route('profile.edit', ['tab' => 'cancellations']));

        $this->assertDatabaseHas('cancellations', [
            'booking_id' => $approvedBooking->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    public function test_user_can_update_avatar_phone_and_address_on_profile(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'user']);
        $avatar = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Raka Petualang',
                'email' => $user->email,
                'phone' => '081345678901',
                'address' => 'Jl. Merapi No. 12, Yogyakarta',
                'avatar' => $avatar,
            ])
            ->assertRedirect('/profile');

        $user->refresh();
        $this->assertSame('Raka Petualang', $user->name);
        $this->assertSame('081345678901', $user->phone);
        $this->assertSame('Jl. Merapi No. 12, Yogyakarta', $user->address);
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }
}
