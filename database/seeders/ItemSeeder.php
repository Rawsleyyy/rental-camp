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
        $lampuCategory = Category::where('slug', 'penerangan-elektronik')->firstOrFail();
        $pakaianCategory = Category::where('slug', 'pakaian-alas-kaki')->firstOrFail();
        $aksesorisCategory = Category::where('slug', 'aksesoris-outdoor')->firstOrFail();
        $paketCategory = Category::where('slug', 'paket-sewa')->firstOrFail();

        $items = [
            [
                'category_id' => $pakaianCategory->id,
                'name' => 'Jaket Gunung The North Face Waterproof',
                'slug' => 'jaket-gunung-the-north-face',
                'description' => 'Jaket gunung windproof & waterproof dengan lapisan seam-sealed dan hoodie pelindung, nyaman untuk cuaca dingin maupun hujan di gunung.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 10,
                'daily_rate' => 40000,
                'image_path' => 'items/jaket-gunung-tnf.jpg',
            ],
            [
                'category_id' => $tendaCategory->id,
                'name' => 'Tenda Family Mansader Kapasitas 6 Orang',
                'slug' => 'tenda-family-mansader-6p',
                'description' => 'Tenda besar berkapasitas 6 orang model kabin dengan teras lega, double-layer tahan hujan dan angin, cocok untuk camping keluarga atau rombongan.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 6,
                'daily_rate' => 125000,
                'image_path' => 'items/tenda-mansader-6p.jpg',
            ],
            [
                'category_id' => $lampuCategory->id,
                'name' => 'Headlamp Streamlight Outdoor LED',
                'slug' => 'headlamp-streamlight-led',
                'description' => 'Lampu kepala LED terang dengan strap elastis kokoh dan sudut pencahayaan fleksibel untuk aktivitas malam serta summit attack.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 18,
                'daily_rate' => 20000,
                'image_path' => 'items/headlamp-streamlight.jpg',
            ],
            [
                'category_id' => $masakCategory->id,
                'name' => 'Alat Masak & Makan Camping Set Enamel',
                'slug' => 'alat-masak-makan-camping-set-enamel',
                'description' => 'Set peralatan makan & saji enamel lengkap untuk rombongan: 4 mug, 4 mangkuk, 4 piring, serta pouch peralatan makan stainless steel.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 12,
                'daily_rate' => 35000,
                'image_path' => 'items/alat-masak-enamel-set.jpg',
            ],
            [
                'category_id' => $aksesorisCategory->id,
                'name' => 'Kursi Lipat Camping Portable',
                'slug' => 'kursi-lipat-camping-portable',
                'description' => 'Kursi lipat outdoor berkerangka besi kuat lengkap dengan sandaran lengan dan cup holder untuk bersantai di area basecamp.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 14,
                'daily_rate' => 25000,
                'image_path' => 'items/kursi-lipat-camping.jpg',
            ],
            [
                'category_id' => $aksesorisCategory->id,
                'name' => 'Tracking Pole Carbon Adjustable',
                'slug' => 'tracking-pole-carbon-adjustable',
                'description' => 'Tongkat pendakian ringan ber-handle ergonomis EVA dengan sistem kunci flip-lock adjustable untuk menjaga keseimbangan di jalur menanjak.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 16,
                'daily_rate' => 20000,
                'image_path' => 'items/tracking-pole-adjustable.jpg',
            ],
            [
                'category_id' => $pakaianCategory->id,
                'name' => 'Sepatu Gunung Hiking Boots Waterproof',
                'slug' => 'sepatu-gunung-hiking-boots-waterproof',
                'description' => 'Sepatu trekking high-cut waterproof dengan sol anti-slip berdaya cengkeram tinggi untuk jalur berbatu, tanah basah, dan pendakian jarak jauh.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 10,
                'daily_rate' => 50000,
                'image_path' => 'items/sepatu-gunung-beretta.jpg',
            ],
            [
                'category_id' => $carrierCategory->id,
                'name' => 'Carrier MIER 75+10L Expedition',
                'slug' => 'carrier-mier-75l-expedition',
                'description' => 'Tas gunung berkapasitas besar 75+10 liter dengan backsystem ergonomis, hip-belt empuk, dan kompartemen luas untuk ekspedisi multi-hari.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 10,
                'daily_rate' => 65000,
                'image_path' => 'items/carrier-mier-75l.jpg',
            ],
            [
                'category_id' => $tendaCategory->id,
                'name' => 'Tenda Dome Toogh Kapasitas 6 Orang',
                'slug' => 'tenda-dome-toogh-6p',
                'description' => 'Tenda dome Toogh berkapasitas 6 orang dengan ventilasi jaring luas, alas waterproof tebal, dan setup cepat untuk camping grup.',
                'type' => 'equipment',
                'is_package' => false,
                'total_stock' => 7,
                'daily_rate' => 115000,
                'image_path' => 'items/tenda-dome-toogh-6p.jpg',
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
