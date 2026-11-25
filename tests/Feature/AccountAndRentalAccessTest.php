<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountAndRentalAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_user_role_can_rent_while_admin_and_staff_are_blocked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);
        $user = User::factory()->create(['role' => 'user', 'phone' => '08123456789']);

        $category = Category::create(['name' => 'Tenda & Shelter', 'slug' => 'tenda-shelter']);
        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'Tenda Dome 4P',
            'slug' => 'tenda-dome-4p',
            'description' => 'Tenda kapasitas 4 orang.',
            'type' => 'equipment',
            'is_package' => false,
            'total_stock' => 5,
            'daily_rate' => 50000,
        ]);

        // Both Admin and Staff are strictly blocked from all rental routes and UI CTAs
        foreach ([$admin, $staff] as $nonRenter) {
            $this->actingAs($nonRenter)->get('/cart')->assertForbidden();
            $this->actingAs($nonRenter)->post('/cart', ['item_id' => $item->id, 'quantity' => 1])->assertForbidden();
            $this->actingAs($nonRenter)->get('/checkout')->assertForbidden();
            $this->actingAs($nonRenter)->getJson('/checkout/check-stock?' . http_build_query([
                'start_date' => now()->addDay()->toDateString(),
                'end_date' => now()->addDays(2)->toDateString(),
            ]))->assertForbidden();
            $this->actingAs($nonRenter)->post('/checkout', [
                'start_date' => now()->addDay()->toDateString(),
                'end_date' => now()->addDays(2)->toDateString(),
                'contact_name' => $nonRenter->name,
                'contact_email' => $nonRenter->email,
                'contact_phone' => '08120000000',
            ])->assertForbidden();
            $this->actingAs($nonRenter)->get('/sewa/' . $item->slug)->assertForbidden();

            $this->actingAs($nonRenter)->get('/')
                ->assertOk()
                ->assertSee('Khusus Penyewa (Role User)')
                ->assertDontSee('+ Tambah ke Keranjang');

            $this->actingAs($nonRenter)->get('/katalog')
                ->assertOk()
                ->assertSee('Khusus Penyewa (Role User)')
                ->assertDontSee('Sewa Sekarang');
        }

        $this->assertSame(0, Cart::count());
        $this->assertSame(0, Booking::count());

        // Normal user can add to cart and complete checkout cleanly
        $this->actingAs($user)
            ->post('/cart', ['item_id' => $item->id, 'quantity' => 1])
            ->assertRedirect('/cart');

        $this->actingAs($user)->get('/cart')->assertOk()->assertSee('Tenda Dome 4P');
        $this->actingAs($user)->get('/checkout')->assertOk();

        $this->actingAs($user)->post('/checkout', [
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'contact_name' => $user->name,
            'contact_email' => $user->email,
            'contact_phone' => $user->phone,
        ])->assertRedirect(route('profile.edit', ['tab' => 'rentals']));

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    public function test_public_registration_creates_usable_user_account_and_blocks_role_tampering(): void
    {
        // Attempt to tamper role='admin' via public /register
        $this->post('/register', [
            'name' => 'Member Baru',
            'email' => 'member.baru@example.test',
            'role' => 'admin',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertRedirect('/');

        $this->assertAuthenticated();
        $created = User::where('email', 'member.baru@example.test')->firstOrFail();
        $this->assertSame('user', $created->role);

        // Newly registered user can access rental cart, but gets 403 on admin/staff panels
        $this->actingAs($created)->get('/cart')->assertOk();
        $this->actingAs($created)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($created)->get('/staff/dashboard')->assertForbidden();

        // Logout & verify newly created account can log back in
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();

        $this->post('/login', [
            'email' => 'member.baru@example.test',
            'password' => 'Password123!',
        ])->assertRedirect('/');
        $this->assertAuthenticatedAs($created);

        // Logout and attempt role='staff' tampering via /register
        $this->post('/logout');
        $this->post('/register', [
            'name' => 'Member Kedua',
            'email' => 'member.kedua@example.test',
            'role' => 'staff',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertRedirect('/');

        $second = User::where('email', 'member.kedua@example.test')->firstOrFail();
        $this->assertSame('user', $second->role);
    }

    public function test_only_admin_can_create_staff_and_manage_user_staff_accounts_with_single_admin_protection(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@demo.test']);
        $staff = User::factory()->create(['role' => 'staff', 'email' => 'staff@demo.test']);
        $user = User::factory()->create(['role' => 'user', 'email' => 'user@demo.test']);

        // Guest is redirected to login
        $this->get('/admin/users')->assertRedirect('/login');

        // User and Staff receive 403 on all Admin user-management routes
        foreach ([$user, $staff] as $nonAdmin) {
            $this->actingAs($nonAdmin)->get('/admin/users')->assertForbidden();
            $this->actingAs($nonAdmin)->get('/admin/users/create')->assertForbidden();
            $this->actingAs($nonAdmin)->post('/admin/users', [
                'name' => 'Unauthorized',
                'email' => 'unauth@example.test',
                'role' => 'staff',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])->assertForbidden();
            $this->actingAs($nonAdmin)->delete('/admin/users/' . $user->id)->assertForbidden();
        }

        // Admin creates a new Staff account
        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Staff Operasional 2',
            'email' => 'staff2@demo.test',
            'role' => 'staff',
            'phone' => '081999888777',
            'address' => 'Basecamp Rawlseyyy',
            'password' => 'StaffPass#2026',
            'password_confirmation' => 'StaffPass#2026',
        ])->assertRedirect('/admin/users');

        $newStaff = User::where('email', 'staff2@demo.test')->firstOrFail();
        $this->assertSame('staff', $newStaff->role);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'CREATE_USER',
        ]);

        // Admin creates a new User account
        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Member Admin Created',
            'email' => 'member.admin@demo.test',
            'role' => 'user',
            'password' => 'UserPass#2026',
            'password_confirmation' => 'UserPass#2026',
        ])->assertRedirect('/admin/users');

        $newUser = User::where('email', 'member.admin@demo.test')->firstOrFail();
        $this->assertSame('user', $newUser->role);

        // Admin cannot create another Admin account via UI/POST
        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Extra Admin',
            'email' => 'admin2@demo.test',
            'role' => 'admin',
            'password' => 'AdminPass#2026',
            'password_confirmation' => 'AdminPass#2026',
        ])->assertSessionHasErrors('role');

        $this->assertSame(1, User::where('role', 'admin')->count());

        // Admin cannot delete the primary Admin account
        $this->actingAs($admin)->delete('/admin/users/' . $admin->id)
            ->assertRedirect('/admin/users')
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);

        // Admin can delete both Staff and User accounts cleanly
        $this->actingAs($admin)->delete('/admin/users/' . $newStaff->id)
            ->assertRedirect('/admin/users')
            ->assertSessionHas('success');
        $this->actingAs($admin)->delete('/admin/users/' . $newUser->id)
            ->assertRedirect('/admin/users')
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $newStaff->id]);
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'DELETE_USER',
        ]);

        // Recreate a temporary staff & verify it can log in and open /staff/dashboard
        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Staff Login Test',
            'email' => 'staff.login@demo.test',
            'role' => 'staff',
            'password' => 'StaffLogin#2026',
            'password_confirmation' => 'StaffLogin#2026',
        ])->assertRedirect('/admin/users');

        $this->post('/logout');
        $this->post('/login', [
            'email' => 'staff.login@demo.test',
            'password' => 'StaffLogin#2026',
        ])->assertRedirect('/staff/dashboard');

        $this->get('/staff/dashboard')->assertOk();
    }
}
