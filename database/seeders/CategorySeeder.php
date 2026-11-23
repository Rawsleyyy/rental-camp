<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tenda & Shelter',
                'slug' => 'tenda-shelter',
            ],
            [
                'name' => 'Tas & Carrier',
                'slug' => 'tas-carrier',
            ],
            [
                'name' => 'Alat Masak & Logistik',
                'slug' => 'alat-masak-logistik',
            ],
            [
                'name' => 'Perlengkapan Tidur',
                'slug' => 'perlengkapan-tidur',
            ],
            [
                'name' => 'Penerangan & Elektronik',
                'slug' => 'penerangan-elektronik',
            ],
            [
                'name' => 'Paket Sewa',
                'slug' => 'paket-sewa',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
