<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Cancellation;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StaffWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_and_admin_can_access_staff_routes_while_user_gets_403(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/staff/dashboard')->assertForbidden();
        $this->actingAs($staff)->get('/staff/dashboard')->assertOk();
        $this->actingAs($admin)->get('/staff/dashboard')->assertOk();
    }

    public function test_staff_login_redirects_to_staff_dashboard(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'email' => 'staff@demo.test',
        ]);

        $this->post('/login', [
            'email' => $staff->email,
            'password' => 'password',
        ])->assertRedirect('/staff/dashboard');
    }

    public function test_full_end_to_end_booking_lifecycle_with_unique_code_and_late_return_penalty(): void
    {
        Storage::fake('public');

        $staff = User::factory()->create(['role' => 'staff']);
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::create(['name' => 'Tenda', 'slug' => 'tenda']);
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Tenda Dome 4P',
            'slug' => 'tenda-dome-4p',
            'description' => 'Tenda 4 orang waterproof.',
            'type' => 'equipment',
            'is_package' => false,
            'total_stock' => 6,
            'daily_rate' => 100000,
        ]);

        // 1. Create pending booking (3 days @ 100k = 300k)
        $startDate = now()->subDays(4)->toDateString();
        $endDate = now()->subDays(2)->toDateString();

        $booking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'RWL-20261122-9001',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => 3,
            'contact_name' => $user->name,
            'contact_email' => $user->email,
            'contact_phone' => '08123456789',
            'total_price' => 300000,
            'status' => 'pending',
        ]);

        BookingItem::create([
            'booking_id' => $booking->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'price_at_booking' => 100000,
        ]);

        // 2. Staff ACC pending booking -> generates 3-digit unique_code
        $this->actingAs($staff)
            ->post(route('staff.bookings.approve', $booking))
            ->assertRedirect();

        $booking->refresh();
        $this->assertSame('approved', $booking->status);
        $this->assertGreaterThanOrEqual(100, $booking->unique_code);
        $this->assertLessThanOrEqual(999, $booking->unique_code);

        // 3. User uploads payment proof -> waiting_verification
        $this->actingAs($user)
            ->post(route('bookings.pay', $booking), [
                'payment_method' => 'Transfer Bank',
                'payment_sender_name' => 'Budi Santoso',
                'payment_proof' => UploadedFile::fake()->image('proof1.png'),
            ])
            ->assertRedirect(route('profile.edit', ['tab' => 'rentals']));

        $booking->refresh();
        $this->assertSame('waiting_verification', $booking->status);

        // 4. Staff rejects payment proof -> returns to approved with payment_rejection_reason
        $this->actingAs($staff)
            ->post(route('staff.bookings.reject-payment', $booking), [
                'payment_rejection_reason' => 'Nominal kurang 3 digit kode unik.',
            ])
            ->assertRedirect();

        $booking->refresh();
        $this->assertSame('approved', $booking->status);
        $this->assertSame('Nominal kurang 3 digit kode unik.', $booking->payment_rejection_reason);

        // 5. User re-uploads payment proof -> waiting_verification
        $this->actingAs($user)
            ->post(route('bookings.pay', $booking), [
                'payment_method' => 'QRIS',
                'payment_sender_name' => 'Budi Santoso',
                'payment_proof' => UploadedFile::fake()->image('proof2.jpg'),
            ])
            ->assertRedirect(route('profile.edit', ['tab' => 'rentals']));

        // 6. Staff verifies payment -> confirmed
        $this->actingAs($staff)
            ->post(route('staff.bookings.verify-payment', $booking))
            ->assertRedirect();

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertNotNull($booking->paid_at);

        // 7. Staff checks out gear -> ongoing + picked_up_at
        $this->actingAs($staff)
            ->post(route('staff.bookings.checkout', $booking))
            ->assertRedirect();

        $booking->refresh();
        $this->assertSame('ongoing', $booking->status);
        $this->assertNotNull($booking->picked_up_at);

        // Overdue appears on Staff dashboard & returns page
        $this->actingAs($staff)->get('/staff/dashboard')->assertOk()->assertSee('Terlambat 2 Hari');
        $this->actingAs($staff)->get('/staff/returns')->assertOk()->assertSee('Overdue 2 Hari');

        // 8. Staff processes late & damaged return (returned_at = today = 2 days after endDate)
        $this->actingAs($staff)
            ->post(route('staff.bookings.return', $booking), [
                'returned_at' => now()->toDateString(),
                'return_condition' => 'rusak_ringan',
                'damage_fee' => 25000,
                'damage_notes' => '1 pasak bengkok.',
                'damaged_item_id' => $item->id,
            ])
            ->assertRedirect(route('staff.bookings.show', $booking));

        $booking->refresh();
        $this->assertSame('returned', $booking->status);
        $this->assertTrue($booking->is_late);
        $this->assertSame(2, $booking->late_days);
        $this->assertEquals(200000, (int) $booking->late_fee);
        $this->assertEquals(25000, (int) $booking->damage_fee);
        $this->assertSame('rusak_ringan', $booking->return_condition);

        $this->assertDatabaseHas('damage_reports', [
            'booking_id' => $booking->id,
            'item_id' => $item->id,
            'penalty_fee' => 25000,
        ]);

        // Verify activity logs recorded staff actions
        $this->assertDatabaseHas('activity_logs', ['user_id' => $staff->id, 'action' => 'ACC_BOOKING']);
        $this->assertDatabaseHas('activity_logs', ['user_id' => $staff->id, 'action' => 'REJECT_PAYMENT']);
        $this->assertDatabaseHas('activity_logs', ['user_id' => $staff->id, 'action' => 'VERIFY_PAYMENT']);
        $this->assertDatabaseHas('activity_logs', ['user_id' => $staff->id, 'action' => 'CHECKOUT_GEAR']);
        $this->assertDatabaseHas('activity_logs', ['user_id' => $staff->id, 'action' => 'RETURN_GEAR']);
    }

    public function test_staff_can_reject_booking_approve_cancellation_and_manage_items_articles(): void
    {
        Storage::fake('public');

        $staff = User::factory()->create(['role' => 'staff']);
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::create(['name' => 'Carrier', 'slug' => 'carrier']);

        // Reject pending booking
        $pendingBooking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'RWL-20261122-9002',
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'total_days' => 3,
            'total_price' => 150000,
            'status' => 'pending',
        ]);

        $this->actingAs($staff)
            ->post(route('staff.bookings.reject', $pendingBooking), [
                'rejection_reason' => 'Stok sedang masuk maintenance.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $pendingBooking->id,
            'status' => 'rejected',
            'rejection_reason' => 'Stok sedang masuk maintenance.',
        ]);

        // Approve cancellation
        $confirmedBooking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'RWL-20261122-9003',
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'total_days' => 2,
            'total_price' => 100000,
            'status' => 'confirmed',
            'paid_at' => now(),
        ]);

        $cancellation = Cancellation::create([
            'booking_id' => $confirmedBooking->id,
            'user_id' => $user->id,
            'reason' => 'Acara dibatalkan.',
            'status' => 'pending',
        ]);

        $this->actingAs($staff)
            ->post(route('staff.cancellations.approve', $cancellation), [
                'refund_amount' => 100000,
                'admin_notes' => 'Refund penuh H-5.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('cancellations', ['id' => $cancellation->id, 'status' => 'approved', 'refund_amount' => 100000]);
        $this->assertDatabaseHas('bookings', ['id' => $confirmedBooking->id, 'status' => 'cancelled']);

        // Staff CRUD Item
        $this->actingAs($staff)
            ->post(route('staff.items.store'), [
                'category_id' => $category->id,
                'name' => 'Matras Angin Ultralight',
                'description' => 'Matras nyaman ringan.',
                'total_stock' => 8,
                'daily_rate' => 25000,
                'image' => UploadedFile::fake()->image('matras.jpg'),
            ])
            ->assertRedirect(route('staff.items.index'));

        $item = Item::where('name', 'Matras Angin Ultralight')->firstOrFail();

        $this->actingAs($staff)
            ->put(route('staff.items.update', $item), [
                'category_id' => $category->id,
                'name' => 'Matras Angin Pro',
                'description' => 'Matras updated.',
                'total_stock' => 10,
                'daily_rate' => 30000,
            ])
            ->assertRedirect(route('staff.items.index'));

        $this->actingAs($staff)
            ->delete(route('staff.items.destroy', $item))
            ->assertRedirect(route('staff.items.index'));

        $this->assertDatabaseMissing('items', ['id' => $item->id]);

        // Staff CRUD Article
        $this->actingAs($staff)
            ->post(route('staff.articles.store'), [
                'title' => 'Panduan Perawatan Frame Tenda',
                'content' => 'Bersihkan frame alloy setelah pendakian.',
            ])
            ->assertRedirect(route('staff.articles.index'));

        $article = Article::where('title', 'Panduan Perawatan Frame Tenda')->firstOrFail();

        $this->actingAs($staff)
            ->delete(route('staff.articles.destroy', $article))
            ->assertRedirect(route('staff.articles.index'));

        $this->assertDatabaseHas('activity_logs', ['user_id' => $staff->id, 'action' => 'CREATE_ITEM']);
        $this->assertDatabaseHas('activity_logs', ['user_id' => $staff->id, 'action' => 'CREATE_ARTICLE']);
    }

    public function test_staff_qr_scan_and_persisted_handover_checklist(): void
    {
        $staff = User::factory()->create(['role' => 'staff', 'name' => 'Petugas Basecamp']);
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::create(['name' => 'Tenda', 'slug' => 'tenda']);

        $tenda = Item::create([
            'category_id' => $category->id,
            'name' => 'Tenda Ultralight 2P',
            'slug' => 'tenda-ultralight-2p',
            'description' => 'Tenda 2P.',
            'type' => 'equipment',
            'is_package' => false,
            'total_stock' => 4,
            'daily_rate' => 65000,
        ]);

        $carrier = Item::create([
            'category_id' => $category->id,
            'name' => 'Carrier 60L',
            'slug' => 'carrier-60l',
            'description' => 'Carrier.',
            'type' => 'equipment',
            'is_package' => false,
            'total_stock' => 4,
            'daily_rate' => 55000,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'RWL-20261122-7171',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'total_days' => 2,
            'contact_name' => $user->name,
            'contact_email' => $user->email,
            'contact_phone' => '081234567892',
            'total_price' => 240000,
            'status' => 'confirmed',
            'paid_at' => now(),
        ]);

        $bi1 = BookingItem::create([
            'booking_id' => $booking->id,
            'item_id' => $tenda->id,
            'quantity' => 1,
            'price_at_booking' => 65000,
        ]);

        $bi2 = BookingItem::create([
            'booking_id' => $booking->id,
            'item_id' => $carrier->id,
            'quantity' => 1,
            'price_at_booking' => 55000,
        ]);

        // Scan page renders & unknown code shows error cleanly
        $this->actingAs($staff)->get('/staff/scan')->assertOk()->assertSee('RWL-20261122-7171');
        $this->actingAs($staff)->post('/staff/scan', ['booking_code' => 'RWL-INVALID-0000'])
            ->assertOk()
            ->assertSee('tidak ditemukan');

        // Scanning valid QR code redirects directly to booking detail
        $this->actingAs($staff)->post('/staff/scan', ['booking_code' => '#RWL-20261122-7171'])
            ->assertRedirect(route('staff.bookings.show', $booking));

        // Partial checklist fails (all items must be checked)
        $this->actingAs($staff)->post(route('staff.bookings.checkout', $booking), [
            'from_checklist' => '1',
            'checked_items' => [$bi1->id],
        ])->assertRedirect()->assertSessionHas('error');

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);

        // Full checklist succeeds & persists to booking_items + booking.handover_staff_id
        $this->actingAs($staff)->post(route('staff.bookings.checkout', $booking), [
            'from_checklist' => '1',
            'checked_items' => [$bi1->id, $bi2->id],
            'item_notes' => [
                $bi1->id => '12 pasak + footprint lengkap',
                $bi2->id => 'Rain cover utuh',
            ],
        ])->assertRedirect();

        $booking->refresh();
        $bi1->refresh();
        $bi2->refresh();

        $this->assertSame('ongoing', $booking->status);
        $this->assertSame($staff->id, $booking->handover_staff_id);
        $this->assertTrue($bi1->is_handover_checked);
        $this->assertTrue($bi2->is_handover_checked);
        $this->assertSame('12 pasak + footprint lengkap', $bi1->handover_notes);
        $this->assertSame('Rain cover utuh', $bi2->handover_notes);

        // /cek-booking Pickup Pass reflects verified basecamp inspection status
        $this->post('/cek-booking', [
            'booking_code' => 'RWL-20261122-7171',
            'contact' => $user->email,
        ])->assertOk()
            ->assertSee('Petugas Basecamp')
            ->assertSee('12 pasak + footprint lengkap')
            ->assertSee('Rain cover utuh');
    }
}
