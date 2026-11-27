<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the 3 Pack & Pitch demo accounts (Admin, Staff, User).
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Admin Pack & Pitch',
                'email' => 'admin@demo.test',
                'password' => Hash::make('Admin#2026'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Basecamp Pack & Pitch Jl. Pinus Raya No. 24',
            ],
            [
                'name' => 'Staff Pack & Pitch',
                'email' => 'staff@demo.test',
                'password' => Hash::make('Staff#2026'),
                'role' => 'staff',
                'phone' => '081234567891',
                'address' => 'Basecamp Pack & Pitch Jl. Pinus Raya No. 24',
            ],
            [
                'name' => 'User Pack & Pitch',
                'email' => 'user@demo.test',
                'password' => Hash::make('User#2026'),
                'role' => 'user',
                'phone' => '081234567892',
                'address' => 'Jl. Merdeka No. 10, Bandung',
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                $account
            );

            $user->forceFill(['email_verified_at' => now()])->save();
        }
    }
}
