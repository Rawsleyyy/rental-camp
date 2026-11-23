<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $tendaCategory = Category::where('slug', 'tenda-shelter')->firstOrFail();
        $carrierCategory = Category::where('slug', 'tas-carrier')->firstOrFail();
        $masakCategory = Category::where('slug', 'alat-masak-logistik')->firstOrFail();
        $tidurCategory = Category::where('slug', 'perlengkapan-tidur')->firstOrFail();
        $lampuCategory = Category::where('slug', 'penerangan-elektronik')->firstOrFail();
        $paketCategory = Category::where('slug', 'paket-sewa')->firstOrFail();

        $items = [
            [
                'category_id' => $tendaCategory->id,
                'name' => 'Tenda Dome Pro 4P',
                'slug' => 'tenda-dome-pro-4p',
                'description' => 'Tenda dome double-layer tahan angin dan hujan dengan rating waterproof 3000mm untuk kapasitas 4 orang.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 8,
                'daily_rate' => 85000,
                'image_path' => null,
            ],
            [
                'category_id' => $tendaCategory->id,
                'name' => 'Tenda Ultralight Solo 2P',
                'slug' => 'tenda-ultralight-solo-2p',
                'description' => 'Tenda ringkas berbobot 1,8 kg dengan frame alloy yang cepat didirikan untuk pendakian solo maupun berdua.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 6,
                'daily_rate' => 65000,
                'image_path' => null,
            ],
            [
                'category_id' => $carrierCategory->id,
                'name' => 'Carrier Expedition 60L',
                'slug' => 'carrier-expedition-60l',
                'description' => 'Tas gunung ergonomis berkapasitas 60 liter dengan backsystem breathable, rangka aluminium, dan rain cover.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 12,
                'daily_rate' => 55000,
                'image_path' => null,
            ],
            [
                'category_id' => $carrierCategory->id,
                'name' => 'Daypack Summit 30L',
                'slug' => 'daypack-summit-30l',
                'description' => 'Daypack ringan berkapasitas 30 liter untuk summit attack atau pendakian tektok.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 10,
                'daily_rate' => 35000,
                'image_path' => null,
            ],
            [
                'category_id' => $masakCategory->id,
                'name' => 'Kompor Windproof & Nesting',
                'slug' => 'kompor-windproof-nesting',
                'description' => 'Paket kompor lipat anti angin lengkap dengan panci nesting aluminium 4 pcs.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 15,
                'daily_rate' => 35000,
                'image_path' => null,
            ],
            [
                'category_id' => $masakCategory->id,
                'name' => 'Cooking Set Titanium 3-in-1',
                'slug' => 'cooking-set-titanium-3-in-1',
                'description' => 'Set peralatan masak ultralight berbahan titanium tahan panas dan mudah dibersihkan.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 9,
                'daily_rate' => 40000,
                'image_path' => null,
            ],
            [
                'category_id' => $tidurCategory->id,
                'name' => 'Sleeping Bag Polar Thermal',
                'slug' => 'sleeping-bag-polar-thermal',
                'description' => 'Sleeping bag hangat dengan lapisan polar lembut hingga suhu 5 derajat celcius dan kondisi selalu steril.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 20,
                'daily_rate' => 30000,
                'image_path' => null,
            ],
            [
                'category_id' => $lampuCategory->id,
                'name' => 'Headlamp Rechargeable 500L',
                'slug' => 'headlamp-rechargeable-500l',
                'description' => 'Lampu kepala tahan cipratan air berdaya 500 lumens dengan mode darurat SOS dan baterai tahan lama.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 18,
                'daily_rate' => 20000,
                'image_path' => null,
            ],
            [
                'category_id' => $paketCategory->id,
                'name' => 'Paket Solo Hiker Hemat (1 Orang)',
                'slug' => 'paket-solo-hiker-hemat',
                'description' => 'Paket lengkap berisi Tenda Ultralight 2P, Carrier 60L, Sleeping Bag Polar, Matras Foil, Kompor Windproof, dan Headlamp.',
                'type' => 'equipment',
                'is_package' => true,
                'total_stock' => 6,
                'daily_rate' => 135000,
                'image_path' => null,
            ],
            [
                'category_id' => $paketCategory->id,
                'name' => 'Paket Duo Camp Nyaman (2 Orang)',
                'slug' => 'paket-duo-camp-nyaman',
                'description' => 'Cocok untuk berdua: 1 Tenda Dome Double-Layer, 2 Sleeping Bag Polar, 2 Matras, 1 Set Alat Masak & Kompor, plus 2 Headlamp.',
                'type' => 'equipment',
                'is_package' => true,
                'total_stock' => 5,
                'daily_rate' => 175000,
                'image_path' => null,
            ],
            [
                'category_id' => $paketCategory->id,
                'name' => 'Paket Grup Basecamp (4 Orang)',
                'slug' => 'paket-grup-basecamp-4p',
                'description' => 'Paket hemat rombongan 4 orang: Tenda Dome Pro 4P, 4 Sleeping Bag, 4 Matras, Cooking Set Lengkap, Flysheet, dan Lampu Tenda.',
                'type' => 'equipment',
                'is_package' => true,
                'total_stock' => 4,
                'daily_rate' => 260000,
                'image_path' => null,
            ],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
