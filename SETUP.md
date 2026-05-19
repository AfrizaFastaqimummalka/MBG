# 🏍️ MBG — My Bike Garage
## Panduan Setup Laravel + Neon PostgreSQL

---

## Prerequisites
- PHP >= 8.2
- Composer
- Node.js >= 18
- Akun Neon DB (https://neon.tech) — gratis

---

## 1. Clone & Install Dependencies

```bash
# Masuk ke folder project
cd mbg-laravel

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

---

## 2. Konfigurasi Environment

```bash
# Salin file env
cp .env.example .env

# Generate app key
php artisan key:generate
```

Edit file `.env`, isi koneksi Neon DB kamu:

```env
APP_NAME="My Bike Garage"
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=ep-xxx.ap-southeast-1.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=your_password_here
DB_SSLMODE=require
```

> Atau gunakan `DATABASE_URL` saja (lihat `.env.example`).

---

## 3. Konfigurasi Database (config/database.php)

Tambahkan `sslmode` di konfigurasi pgsql:

```php
// config/database.php
'pgsql' => [
    'driver'   => 'pgsql',
    'host'     => env('DB_HOST', '127.0.0.1'),
    'port'     => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'laravel'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset'  => 'utf8',
    'prefix'   => '',
    'schema'   => 'public',
    'sslmode'  => env('DB_SSLMODE', 'require'),  // ← tambahkan ini
],
```

---

## 4. Jalankan Migrasi

```bash
php artisan migrate
```

Ini akan membuat tabel:
- `users` (+ kolom phone, avatar_url)
- `vehicles`
- `services`
- `spareparts`

---

## 5. Build Frontend

```bash
# Development (dengan hot-reload)
npm run dev

# Production build
npm run build
```

---

## 6. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser: http://localhost:8000

---

## Struktur File yang Dihasilkan

```
mbg-laravel/
├── app/
│   ├── Helpers/
│   │   └── FormatHelper.php         ← rp(), fd(), relDate(), initials(), getStatus()
│   ├── Http/Controllers/
│   │   ├── AuthController.php       ← login, register, forgot, logout
│   │   ├── HomeController.php       ← garasi + tambah/hapus kendaraan
│   │   ├── ServiceController.php    ← riwayat service
│   │   ├── ExpenseController.php    ← rekap pengeluaran
│   │   ├── SparepartController.php  ← pantau sparepart
│   │   └── AccountController.php   ← profil & tips
│   └── Models/
│       ├── User.php
│       ├── Vehicle.php
│       ├── ServiceRecord.php        ← tabel: services
│       └── Sparepart.php
├── database/migrations/
│   ├── ..._create_vehicles_table.php
│   ├── ..._create_services_table.php
│   └── ..._create_spareparts_table.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php            ← layout utama + bottom nav + toast
│   │   └── auth.blade.php           ← layout halaman auth
│   ├── auth/
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   └── forgot-password.blade.php
│   ├── home/index.blade.php         ← Garasi
│   ├── service/index.blade.php      ← Service
│   ├── expense/index.blade.php      ← Pengeluaran
│   ├── parts/index.blade.php        ← Sparepart
│   └── account/index.blade.php      ← Akun
└── routes/web.php
```

---

## Deploy ke Production (Vercel / Railway / VPS)

### Railway.app (Recommended)
1. Push ke GitHub
2. Buat project baru di Railway
3. Tambah service Laravel + PostgreSQL Neon
4. Set environment variables dari `.env`
5. Build command: `composer install --no-dev && npm run build && php artisan migrate --force`

### VPS / Shared Hosting
```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Tips Keamanan
- Pastikan `APP_DEBUG=false` di production
- Gunakan HTTPS (Neon DB butuh SSL)
- Jalankan `php artisan storage:link` jika pakai file upload
- Set `SESSION_SECURE_COOKIE=true` di production

---

## Troubleshooting

**Error: "could not connect to server"**
→ Cek `DB_HOST`, `DB_PASSWORD`, dan pastikan `DB_SSLMODE=require`

**Error: "Class App\Models\ServiceRecord not found"**
→ Jalankan `composer dump-autoload`

**Toast tidak muncul**
→ Pastikan Lucide CDN ter-load dan `lucide.createIcons()` dipanggil setelah DOM ready
