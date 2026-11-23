# ⛺ Rawlseyyy — Sistem Informasi Persewaan Alat Camping

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**Rawlseyyy** adalah aplikasi web persewaan alat camping berbasis **Laravel** yang dirancang untuk memudahkan proses pemesanan perlengkapan mendaki secara *online*, mulai dari validasi ketersediaan stok per rentang tanggal, pembayaran terverifikasi dengan kode unik, kartu pengambilan berbasis QR Code, hingga pemantauan pengembalian dan perhitungan denda keterlambatan secara otomatis.

---

## ✨ Fitur Utama

### 🌐 Pengunjung (Publik)
- **Homepage & Katalog Alat:** Ringkasan alat camping unggulan, paket sewa, panduan reservasi, serta halaman katalog lengkap (`/katalog`) dengan filter kategori dan pencarian.
- **Artikel Edukasi Camping:** Informasi dan tips seputar pendakian serta pemilihan alat (`/artikel`).
- **Cek Booking & Kartu Pengambilan:** Verifikasi status pesanan menggunakan kode booking + email/telepon (`/cek-booking`) yang menampilkan **QR Code SVG** dan *checklist* serah terima alat saat status sudah `confirmed`.
- **Statistik Penyewaan:** Visualisasi interaktif menggunakan **Chart.js** (`/statistik`) untuk tren penyewaan bulanan, data pembatalan, dan tingkat pemakaian stok alat.

### 🎒 User (Penyewa)
- **Keranjang Sewa & Checkout:** Tambah alat/paket ke keranjang (`/cart`) dan atur jumlah unit sebelum mengajukan sewa.
- **Validasi Stok Real-Time per Rentang Tanggal:** Mencegah bentrok jadwal (*double booking*) dengan mengecek sisa stok aktif pada tanggal mulai hingga selesai sewa.
- **Pembayaran 2 Tahap (Anti-Penipuan):** Nominal transfer dilengkapi **3 digit kode unik** dan wajib mengunggah bukti transfer sebelum diverifikasi Staff.
- **Weather-Smart Suggestion:** Rekomendasi perlengkapan tambahan (seperti *flysheet* dan jas hujan) berdasarkan prakiraan cuaca lokasi tujuan menggunakan OpenWeatherMap API.
- **Profil & Riwayat Sewa (`/profile`):** Manajemen data pribadi & foto profil, pantau status sewa secara *real-time*, akses kartu QR, dan form pengajuan pembatalan.

### 📋 Staff (Operasional)
- **Dashboard Monitoring:** Ringkasan pesanan baru, pembayaran menunggu verifikasi, penyewaan aktif (`ongoing`), serta peringatan merah untuk penyewa yang melewati batas waktu pengembalian (*Overdue*).
- **Persetujuan Sewa & Verifikasi Pembayaran:** Menyetujui/menolak permintaan sewa beserta alasan, serta memverifikasi foto bukti transfer dan kode unik.
- **Serah Terima & Pengembalian Otomatis:** Proses *check-out* alat ke penyewa, pencatatan kondisi alat (baik/rusak/hilang), serta kalkulasi otomatis **denda keterlambatan** dan laporan kerusakan (`damage_reports`).
- **Approval Pembatalan & Manajemen Konten:** Persetujuan pengajuan pembatalan penyewa serta CRUD data alat camping dan artikel.
- **Audit Trail Otomatis:** Setiap tindakan penting Staff tercatat otomatis ke dalam tabel `activity_logs`.

### 🛡️ Admin
- **Manajemen Akun:** Pengelolaan data akun User dan Staff.
- **Master Data:** Pengelolaan penuh kategori, alat camping, dan artikel.
- **Monitoring Log Aktivitas:** Memantau riwayat tindakan operasional Staff dan laporan kerusakan alat.

---

## 🔄 Alur Status Transaksi

```text
[User Checkout] ──► pending
                      │
        ┌─────────────┴─────────────┐
        ▼ (Ditolak Staff)           ▼ (Disetujui Staff + Kode Unik)
    rejected                    approved
                                    │
                                    ▼ (User Upload Bukti Bayar)
                          waiting_verification
                                    │
        ┌───────────────────────────┴───────────────────────────┐
        ▼ (Bukti Ditolak -> Kembali ke approved)                ▼ (Valid)
    approved                                                confirmed (QR Aktif)
                                                                │
                                                                ▼ (Alat Diambil)
                                                             ongoing
                                                                │
                                                                ▼ (Alat Kembali + Cek Denda)
                                                             returned
```

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | Laravel (PHP 8.2+) |
| **Autentikasi** | Laravel Breeze |
| **Frontend & Styling** | Blade Template, Tailwind CSS, Alpine.js, AOS (Animate On Scroll) |
| **Visualisasi Data** | Chart.js |
| **Database** | MySQL |
| **Integrasi Eksternal** | OpenWeatherMap API *(Weather-Smart Suggestion)* |
| **Local Development** | Laravel Herd / XAMPP |

---

## 📸 Tangkapan Layar

> Simpan file gambar ke dalam folder `docs/screenshots/` lalu sesuaikan nama filenya di bawah ini.

<table>
  <tr>
    <td align="center">
      <img src="docs/screenshots/homepage.png" width="420" alt="Homepage Rawlseyyy" /><br />
      <sub><b>Homepage & Katalog Unggulan</b></sub>
    </td>
    <td align="center">
      <img src="docs/screenshots/katalog.png" width="420" alt="Katalog & Checkout" /><br />
      <sub><b>Katalog & Validasi Stok per Tanggal</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center">
      <img src="docs/screenshots/profile-rentals.png" width="420" alt="Riwayat Sewa & QR" /><br />
      <sub><b>Riwayat Sewa & Kartu Pengambilan QR</b></sub>
    </td>
    <td align="center">
      <img src="docs/screenshots/staff-dashboard.png" width="420" alt="Dashboard Staff" /><br />
      <sub><b>Dashboard Staff & Monitoring Pengembalian</b></sub>
    </td>
  </tr>
</table>

---

## 🚀 Cara Instalasi & Menjalankan Proyek

### 1. Clone Repository
```bash
git clone [https://github.com/Rawsleyyy/rental-camp.git](https://github.com/Rawsleyyy/rental-camp.git)
cd rental-camp
```

### 2. Install Dependensi
```bash
composer install
npm install && npm run build
```

### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`, lalu buat *application key*:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan pengaturan database (dan API key cuaca bila digunakan) di dalam file `.env`:
```env
APP_NAME="Rawlseyyy"
APP_URL=[http://rental-camp.test](http://rental-camp.test)

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_camp
DB_USERNAME=root
DB_PASSWORD=

OPENWEATHER_API_KEY=your_openweathermap_api_key
```

> **Catatan untuk pengguna XAMPP / MariaDB versi lama:** Pastikan `collation` pada `config/database.php` (bagian `mysql`) menggunakan `utf8mb4_unicode_ci` agar migrasi berjalan tanpa error `Unknown collation`.

### 4. Migrasi, Seeder, & Storage Link
Pastikan database `rental_camp` sudah dibuat di MySQL, lalu jalankan:
```bash
php artisan migrate --seed
php artisan storage:link
```

### 5. Jalankan Aplikasi
- **Menggunakan Laravel Herd:** Daftarkan folder `rental-camp` di menu *Sites* pada Laravel Herd, lalu buka `http://rental-camp.test`.
- **Menggunakan Artisan Server:**
  ```bash
  php artisan serve
  ```
  Buka aplikasi melalui `http://127.0.0.1:8000`.

---

## 🔐 Akun Demo

Database seeder sudah menyediakan akun uji coba untuk masing-masing *role*:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@demo.test` | `Admin#2026` |
| **Staff** | `staff@demo.test` | `Staff#2026` |
| **User** | `user@demo.test` | `User#2026` |

---

## 👨‍💻 Author

**Narendra Lintang Saputra**
- GitHub: [@Rawsleyyy](https://github.com/Rawsleyyy)

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademik (Tugas UAS) dan bebas dipelajari dengan tetap mencantumkan kredit pembuatnya.
