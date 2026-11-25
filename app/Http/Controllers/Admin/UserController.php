<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display paginated list of accounts with role filtering and search.
     */
    public function index(Request $request): View
    {
        $roleFilter = trim((string) $request->query('role', 'all'));
        $search = trim((string) $request->query('q', ''));

        $counts = [
            'all' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'user' => User::where('role', 'user')->count(),
        ];

        $query = User::query()
            ->orderByRaw("CASE role WHEN 'admin' THEN 0 WHEN 'staff' THEN 1 ELSE 2 END")
            ->orderByDesc('id');

        if (in_array($roleFilter, ['admin', 'staff', 'user'], true)) {
            $query->where('role', $roleFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(12)->withQueryString();

        return view('admin.users.index', compact('users', 'roleFilter', 'search', 'counts'));
    }

    /**
     * Show form to create a new Staff or User account (Admin creation is disallowed via UI).
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created Staff or User account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:user,staff'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'role.in' => 'Role akun yang dapat dibuat hanya Staff atau User. Akun Admin hanya dikelola melalui source code.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        ActivityLogger::log(
            'CREATE_USER',
            "Menambahkan akun {$user->role} baru: {$user->name} ({$user->email})."
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun {$user->role} atas nama {$user->name} berhasil dibuat.");
    }

    /**
     * Delete a Staff or User account (primary Admin account is strictly protected).
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'admin' || $user->id === Auth::id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Akun Admin utama tidak dapat dihapus.');
        }

        $name = $user->name;
        $email = $user->email;
        $role = $user->role;

        $this->deletePublicImage($user->avatar);
        $user->delete();

        ActivityLogger::log(
            'DELETE_USER',
            "Menghapus akun {$role}: {$name} ({$email})."
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun {$role} ({$name}) berhasil dihapus.");
    }
}
