<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Akun Demo Rawlseyyy:
     * - Admin: admin@demo.test / Admin#2026
     * - Staff: staff@demo.test / Staff#2026
     * - User:  user@demo.test  / User#2026
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
        ]);

        $admin = User::where('email', 'admin@demo.test')->first();

        if ($admin) {
            $articles = [
                [
                    'user_id' => $admin->id,
                    'title' => 'Tips Memilih Tenda untuk Pemula',
                    'slug' => 'tips-memilih-tenda-untuk-pemula',
                    'content' => "Memilih tenda yang tepat adalah kunci kenyamanan saat pertama kali berkemah. Pertama, tentukan kapasitas yang sesuai jumlah anggota (tambahkan margin 1 orang agar ruang gerak dan penyimpanan carrier tetap lega).\n\nKedua, pilih tenda double-layer dengan inner breathable dan outer flysheet ber-rating waterproof minimal 2500-3000mm agar aman menghadapi hujan deras dan embun gunung. Ketiga, perhatikan bobot total dan material frame—frame alloy lebih ringan dan lentur dibanding fiberglass.\n\nTerakhir, selalu siapkan footprint atau alas tambahan sebelum mendirikan tenda untuk mencegah rembesan air dari tanah dan melindungi alas tenda dari benda tajam.",
                    'image_path' => null,
                ],
                [
                    'user_id' => $admin->id,
                    'title' => 'Checklist Alat Camping Wajib',
                    'slug' => 'checklist-alat-camping-wajib',
                    'content' => "Agar petualangan berjalan aman tanpa ada barang krusial yang tertinggal, susun daftar perlengkapan dalam 4 kelompok utama: Shelter, Tidur, Logistik, dan Keamanan.\n\n1. Shelter: Tenda double-layer, pasak lengkap, tali guylines, dan footprint.\n2. Tidur: Sleeping bag sesuai suhu lokasi, matras foil/angin untuk insulasi tanah dingin, dan pakaian ganti kering.\n3. Logistik & Masak: Kompor windproof, gas canister cadangan, nesting, korek api tahan angin, serta persediaan air minum cukup.\n4. Keamanan & Penerangan: Headlamp dengan baterai penuh, jas hujan/ponco, kotak P3K pribadi, dan trash bag untuk membawa turun semua sampah.",
                    'image_path' => null,
                ],
                [
                    'user_id' => $admin->id,
                    'title' => 'Tips Camping Minimalis & Nyaman',
                    'slug' => 'tips-camping-minimalis-nyaman',
                    'content' => "Camping tidak harus membawa beban berlebih. Dengan pendekatan minimalis, Anda tetap bisa menikmati suasana alam secara maksimal sambil menjaga punggung tetap ringan.\n\nMulailah dengan menyewa paket alat multifungsi atau gear ultralight seperti cooking set titanium dan tenda ber-frame alloy. Susun barang menggunakan teknik layering di dalam carrier: taruh sleeping bag dan pakaian di dasar, alat berat seperti tenda dan logistik menempel di bagian punggung tengah, serta barang yang sering diakses di kompartemen atas.\n\nDengan pemilihan perlengkapan yang ringkas dan efisien dari Rawlseyyy, setup basecamp jadi lebih cepat dan waktu istirahat Anda di alam jauh lebih berkualitas.",
                    'image_path' => null,
                ],
            ];

            foreach ($articles as $article) {
                Article::updateOrCreate(
                    ['slug' => $article['slug']],
                    $article
                );
            }
        }
    }
}
