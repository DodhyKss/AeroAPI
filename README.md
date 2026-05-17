# 🚀 AeroAPI Framework

AeroAPI adalah framework PHP MVC kustom yang dirancang dengan performa tinggi, efisiensi memori maksimal, dan ramah pengembang (*developer-friendly*). Framework ini dilengkapi dengan integrasi bawaan **Laravel Eloquent ORM**, sistem **IoC Service Autowiring Container**, serta **High-Precision API Benchmarking & Speed Tester** bawaan.

---

## 🌟 Fitur Utama

- **Laravel Eloquent ORM**: Menggunakan ActiveRecord kustom yang tangguh untuk memanipulasi database dengan sintaksis elegan Laravel.
- **High-Precision Processing**: Melacak waktu pemrosesan server murni dalam skala mikrosekon menggunakan konstanta `AERO_START`.
- **RESTful Router & Grouping**: Dukungan native untuk method `GET`, `POST`, `PUT`, `DELETE` serta grouping prefix dan middleware stack.
- **IoC Container & Autowiring**: Dependency injection otomatis berbasis PHP Reflection untuk class service pada constructor controller.
- **Unified Exception Handler**: AeroAPI Debug Page yang interaktif pada mode pengembangan (`APP_DEBUG=true`) dan payload JSON aman pada mode produksi (`APP_DEBUG=false`).
- **Interactive CLI Benchmarking**: Alat stress-testing API berkecepatan tinggi dengan visualisasi statistik Klien (Round-Trip) vs Server (AeroAPI murni) langsung di terminal Anda.

---

## 🧭 Navigasi Dokumentasi

1. [Instalasi & Persiapan Awal](#1-instalasi--persiapan-awal)
2. [Database & ORM Setup](#2-database--orm-setup)
3. [Models & Eloquent CRUD](#3-models--eloquent-crud)
4. [Routing & Controller](#4-routing--controller)
5. [Services & IoC Container](#5-services--ioc-container)
6. [Middleware Registration](#6-middleware-registration)
7. [Error Handling & Debugging](#7-error-handling--debugging)
8. [API Benchmarking Tool](#8-api-benchmarking-tool)
9. [CLI Commands Reference](#9-cli-commands-reference)

---

## 1. Instalasi & Persiapan Awal

### Langkah A: Clone & Unduh Dependensi PHP
Jalankan Composer di terminal proyek untuk menginstal seluruh dependensi framework eksternal:
```bash
composer install
```

### Langkah B: Konfigurasi Environment (`.env`)
Salin file template `.env.example` menjadi `.env` pada root direktori proyek, kemudian sesuaikan parameter koneksi database Anda:
```env
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=

# Set true untuk development, false untuk production
APP_DEBUG=true
```

### Langkah C: Migrasi & Seeding Database
Buat database baru di DBMS Anda (seperti MySQL), lalu jalankan perintah CLI AeroAPI untuk mengisi skema tabel dan data awal:
```bash
# A. Buat struktur tabel database bawaan
php aero migrate

# B. Isi tabel dengan data seeder awal (opsional)
php aero seed
```

### Langkah D: Menjalankan Server Lokal
Mulai server web pengembangan lokal AeroAPI Anda dengan menggunakan built-in CLI server:
```bash
# Server akan aktif di http://localhost:8000
php aero serve
```

---

## 2. Database & ORM Setup

AeroAPI secara dinamis mendeteksi database driver Anda melalui parameter koneksi di file `.env`. Framework ini mendukung SQLite, MySQL, dan PostgreSQL secara native dengan penanganan path SQLite terisolasi.

| Database | Contoh Konfigurasi Driver di `.env` |
| :--- | :--- |
| **SQLite** | `DB_DRIVER=sqlite` <br> `DB_DATABASE=database/database.sqlite` |
| **MySQL** | `DB_DRIVER=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=aero_db` |
| **PostgreSQL** | `DB_DRIVER=pgsql`, `DB_HOST=127.0.0.1`, `DB_PORT=5432`, `DB_DATABASE=aero_db` |

---

## 3. Models & Eloquent CRUD

Semua model Anda ditempatkan di direktori `app/Models/` dan mewarisi fungsionalitas Laravel Eloquent ORM:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    public $timestamps = false;
}
```

### Contoh Query CRUD di Controller:
```php
use App\Models\User;

// 1. Ambil semua Data
$users = User::all();

// 2. Insert Data Baru
$user = User::create([
    'name' => 'Budi Utomo',
    'email' => 'budi@example.com',
    'password' => password_hash('password123', PASSWORD_BCRYPT)
]);

// 3. Update Data
$user = User::find(1);
if ($user) {
    $user->name = 'Budi Ganteng';
    $user->save();
}

// 4. Hapus Data
User::destroy(2);
```

---

## 4. Routing & Controller

AeroAPI memiliki sistem routing elegan di folder `routes/web.php` untuk memetakan URL langsung ke method Controller Anda.

### A. Registrasi RESTful Routing (`routes/web.php`):
```php
use Core\Route;
use App\Controllers\HomeController;
use App\Controllers\ApiController;

// Standard Web Routing
Route::get('/', [HomeController::class, 'index']);
Route::post('/api/submit', [HomeController::class, 'submitData']);

// RESTful HTTP API Routing (GET, POST, PUT, DELETE)
Route::get('/api/items', [ApiController::class, 'getItems']);
Route::get('/api/items/detail', [ApiController::class, 'getItemDetail']);
Route::post('/api/items', [ApiController::class, 'createItem']);
Route::put('/api/items', [ApiController::class, 'updateItem']);
Route::delete('/api/items', [ApiController::class, 'deleteItem']);
```

### B. Route Grouping (Prefix & Middleware):
```php
use Core\Route;
use App\Controllers\UserController;
use App\Middlewares\AuthMiddleware;

// Semua route di dalam grup ini otomatis memiliki prefix /api dan melewati AuthMiddleware
Route::group(['prefix' => '/api', 'middleware' => AuthMiddleware::class], function() {
    
    // URL: /api/users
    Route::get('/users', [UserController::class, 'index']);
    
    // URL: /api/profile
    Route::get('/profile', [UserController::class, 'profile']);
});
```

---

## 5. Services & IoC Container

AeroAPI dilengkapi dengan IoC Container yang powerful untuk melakukan *autowiring* dependensi class secara otomatis melalui constructor Controller menggunakan PHP Reflection.

### Langkah A: Buat Class Service (`app/Services/UserService.php`)
```php
<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getActiveUsersCount(): int
    {
        return User::count();
    }
}
```

### Langkah B: Autowiring Dependency di Controller
Cukup lakukan type-hint service tersebut pada constructor controller Anda, IoC Container akan menyuntikkannya secara otomatis!
```php
namespace App\Controllers;

use Core\Controller;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    // UserService otomatis di-inject di sini!
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $count = $this->userService->getActiveUsersCount();
        return $this->json(['active_users' => $count]);
    }
}
```

---

## 6. Middleware Registration

### Metode A: Route-Level (Di routes/web.php)
```php
use Core\Route;
use App\Middlewares\AuthMiddleware;

Route::get('/api/users', [\App\Controllers\UserController::class, 'index'])
    ->middleware(AuthMiddleware::class);
```

### Metode B: Controller-Level (Di Controller Constructor)
```php
class UserController extends Controller
{
    public function __construct()
    {
        // Hanya jalankan AuthMiddleware pada action 'index'
        $this->registerMiddleware(new AuthMiddleware(['index']));
    }
}
```

---

## 7. Error Handling & Debugging

AeroAPI mendeteksi parameter `APP_DEBUG` di file `.env` secara global untuk mengontrol keluaran detail error.

### A. Mode Debug Aktif (`APP_DEBUG=true`)
Ketika terjadi error koding atau syntax PHP kustom, AeroAPI akan menghentikan eksekusi dan merender **AeroAPI Debug Page** yang berisi Exception Class, Error Message, Lokasi file absolut beserta baris tepat terjadinya error, dan Stack Trace lengkap.

### B. Mode Produksi (`APP_DEBUG=false`)
AeroAPI mengamankan aplikasi dengan menyembunyikan detail teknis:
* **Web Request**: Merender halaman error ramah pengguna (`app/Views/error.php`).
* **API Request (JSON)**: Mengembalikan respons JSON standar yang aman:
  ```json
  {
      "status": "error",
      "code": 500,
      "message": "Terjadi kesalahan internal server."
  }
  ```

---

## 8. API Benchmarking Tool

AeroAPI dilengkapi dengan **High-Precision API Benchmarking & Speed Tester** bawaan berbasis CLI yang memungkinkan Anda melakukan stres-tes serta mengukur performa API secara real-time.

### Cara Menulis Skenario Pengujian (`tests/api/benchmark_post.php`):
Kembalikan array konfigurasi pengujian:
```php
<?php

return [
    'name' => 'REST POST - Tambah Item Baru',
    'url' => 'http://localhost:8000/api/items',
    'method' => 'POST',
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
    'body' => json_encode([
        'title' => 'Judul Baru',
        'body' => 'Isi item baru dari benchmark.'
    ]),
    'requests' => 15, // Jumlah request stres-tes yang dikirim
];
```

### Menjalankan Benchmarking di CLI:
```bash
# A. Memunculkan Menu Pilihan Interaktif berkas pengujian (0-7)
php aero benchmark

# B. Menjalankan secara langsung berkas pengujian nomor indeks 3 (bypass menu)
php aero benchmark 3

# C. Menjalankan berkas pengujian spesifik berdasarkan nama berkas
php aero benchmark benchmark_users.php
```

---

## 9. CLI Commands Reference

AeroAPI CLI menyediakan perintah bawaan untuk membantu pengembangan sehari-hari:

| Command | Deskripsi |
| :--- | :--- |
| `php aero serve` | Menjalankan web server pengembangan lokal di `http://localhost:8000` |
| `php aero serve [port]` | Menjalankan web server pengembangan lokal di port kustom (misal: `8080`) |
| `php aero migrate` | Menjalankan migrasi struktur database ke DBMS Anda |
| `php aero seed` | Mengisi database dengan data awal / dummy dari folder `seeders/` |
| `php aero benchmark` | Menjalankan alat uji kecepatan API (memunculkan menu interaktif) |

---

Selamat mengembangkan aplikasi hebat dengan **AeroAPI Framework**! 🚀
