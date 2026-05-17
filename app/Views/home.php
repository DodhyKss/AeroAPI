<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AeroAPI - Dokumentasi Pengembang</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Fira+Code:wght@400;500&display=swap');
        
        :root {
            --bg: #0b0f17;
            --surface: #121824;
            --surface-hover: #1b2334;
            --border: #1e293b;
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg); 
            color: var(--text-main); 
            margin: 0; 
            padding: 0; 
            min-height: 100vh; 
            display: flex;
            flex-direction: column;
        }

        /* Header */
        header {
            width: 100%;
            padding: 16px 40px;
            background: #0f131a;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-symbol {
            width: 32px;
            height: 32px;
            background: var(--primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .status-badges {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            font-family: 'Fira Code', monospace;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            color: var(--text-muted);
        }

        .badge-success {
            background: #022c22;
            color: #34d399;
            border-color: #065f46;
        }

        .badge-danger {
            background: #450a0a;
            color: #f87171;
            border-color: #7f1d1d;
        }

        .badge-info {
            background: #172554;
            color: #60a5fa;
            border-color: #1e3a8a;
        }

        /* Layout */
        .layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            max-width: 1300px;
            width: 100%;
            margin: 40px auto;
            padding: 0 40px;
            gap: 50px;
            flex-grow: 1;
        }

        /* Sidebar Navigation */
        .sidebar {
            position: sticky;
            top: 90px;
            height: fit-content;
        }

        .nav-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .nav-card h3 {
            margin-top: 0;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-links a {
            display: block;
            padding: 8px 14px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            border-left: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-links a:hover {
            color: var(--text-main);
            border-left: 2px solid rgba(255, 255, 255, 0.2);
        }

        .nav-links a.active {
            color: var(--primary);
            border-left: 2px solid var(--primary);
            font-weight: 600;
        }

        /* Content Area */
        .content {
            display: flex;
            flex-direction: column;
            gap: 50px;
        }

        .doc-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 36px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .doc-section h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--text-main);
            border-bottom: 1px solid var(--border);
            padding-bottom: 10px;
        }

        .doc-section p {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Code Blocks */
        .code-container {
            margin: 20px 0;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: #0d1117;
        }

        .code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #161b22;
            padding: 8px 16px;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            font-family: 'Fira Code', monospace;
        }

        .code-lang {
            font-weight: 600;
            text-transform: uppercase;
            color: var(--primary);
        }

        pre {
            margin: 0;
            padding: 16px;
            overflow-x: auto;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #c9d1d9;
        }

        /* Config Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        th, td {
            padding: 12px 18px;
            text-align: left;
            font-size: 0.9rem;
        }

        th {
            background: rgba(255, 255, 255, 0.02);
            font-weight: 600;
            color: white;
            border-bottom: 1px solid var(--border);
        }

        td {
            color: var(--text-muted);
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .highlight-text {
            color: #38bdf8;
            font-family: 'Fira Code', monospace;
            background: rgba(255, 255, 255, 0.04);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.85em;
        }

        /* Action Buttons */
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background-color 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: var(--primary-hover);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.15);
        }

        /* Footer */
        footer {
            width: 100%;
            padding: 24px;
            background: #0f131a;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: auto;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .layout {
                grid-template-columns: 1fr;
                padding: 0 20px;
                gap: 30px;
            }
            .sidebar {
                position: relative;
                top: 0;
            }
            header {
                padding: 16px 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo-container">
            <img src="/logo.png" alt="Favicon" style="width: 32px; height: 32px; border-radius: 4px; object-fit: cover; mix-blend-mode: screen;">
            <div class="logo-text" style="font-size: 1.25rem; font-weight: 700; letter-spacing: 0.5px; color: var(--text-main);">AeroAPI</div>
        </div>
        <div class="status-badges">
            <div class="badge badge-success">System: Online</div>
            <div class="badge <?= ($dbStatus ?? 'Disconnected') === 'Connected' ? 'badge-success' : 'badge-danger' ?>">
                DB: <?= htmlspecialchars($dbStatus ?? 'Disconnected') ?>
            </div>
            <div class="badge badge-info">Driver: <?= htmlspecialchars($dbDriver ?? 'None') ?></div>
            <?php if (($userCount ?? 0) > 0): ?>
                <div class="badge badge-info" style="background: #3b0764; color: #d8b4fe; border-color: #581c87;">
                    Users: <?= htmlspecialchars($userCount ?? 0) ?> Seeded
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Layout Grid -->
    <div class="layout">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="nav-card">
                <h3>Dokumentasi</h3>
                <ul class="nav-links">
                    <li><a href="#overview" class="active">Overview</a></li>
                    <li><a href="#installation">Instalasi</a></li>
                    <li><a href="#database">Database & ORM</a></li>
                    <li><a href="#models">Models & CRUD</a></li>
                    <li><a href="#routing">Routing & Controller</a></li>
                    <li><a href="#services">Services & IoC</a></li>
                    <li><a href="#middleware">Middleware</a></li>
                    <li><a href="#debugging">Error & Debugging</a></li>
                    <li><a href="#benchmarking">API Benchmarking</a></li>
                    <li><a href="#cli">CLI Commands</a></li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="content">
            
            <!-- Section: Overview -->
            <section id="overview" class="doc-section">
                <h2>Overview</h2>
                <p>Selamat datang di AeroAPI Framework. Framework MVC yang minimalis, cepat, dan ringan. AeroAPI dirancang untuk mendukung performa tinggi dengan kebutuhan memori sekecil mungkin. Framework ini sekarang telah terintegrasi penuh dengan Laravel Eloquent ORM.</p>
                <p>AeroAPI dilengkapi dengan fitur **High-Precision Execution Benchmarking** bawaan yang secara otomatis mengukur waktu pemrosesan internal server dari sejak request masuk hingga respons selesai dikembalikan ke klien.</p>
                
                <div class="btn-group" style="margin-bottom: 30px;">
                    <a href="/api/data" class="btn">Test API JSON Response</a>
                    <a href="#database" class="btn btn-outline">Mulai Konfigurasi DB</a>
                </div>

                <h3>Interactive API Tester</h3>
                <p>Uji coba endpoint API Anda secara real-time langsung dari panel di bawah ini:</p>

                <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border); border-radius: 8px; padding: 20px; margin-top: 15px;">
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
                        <button onclick="testApi('/users', 'GET')" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.85rem;">GET /users</button>
                        <button onclick="testApi('/api/data', 'GET')" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.85rem;">GET /api/data</button>
                        <button onclick="testApi('/api/submit', 'POST', {'Authorization': 'Bearer rahasia123'})" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.85rem; border-color: #065f46; color: #34d399;">POST /api/submit (Valid Token)</button>
                        <button onclick="testApi('/api/submit', 'POST')" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.85rem; border-color: #7f1d1d; color: #f87171;">POST /api/submit (No Token)</button>
                    </div>

                    <div style="font-family: 'Fira Code', monospace; font-size: 0.85rem; background: #0d1117; border: 1px solid var(--border); border-radius: 6px; overflow: hidden;">
                        <div style="background: #161b22; padding: 8px 16px; color: var(--text-muted); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
                            <span>Console Output <span id="api-latency" style="margin-left: 10px; color: #38bdf8; font-size: 0.8rem; display: none;">(0 ms)</span></span>
                            <span id="api-status" class="badge">IDLE</span>
                        </div>
                        <pre id="api-output" style="margin: 0; padding: 16px; max-height: 250px; overflow-y: auto; color: #c9d1d9;">Klik salah satu tombol di atas untuk menjalankan uji coba API secara real-time.</pre>
                    </div>
                </div>
            </section>

            <!-- Section: Installation -->
            <section id="installation" class="doc-section">
                <h2>Instalasi & Persiapan Awal</h2>
                <p>Ikuti langkah-langkah di bawah ini untuk memasang AeroAPI Framework pertama kali di lingkungan pengembangan lokal Anda.</p>

                <h3>1. Clone & Unduh Dependensi</h3>
                <p>Unduh file proyek framework lalu jalankan Composer di terminal untuk menginstal seluruh dependensi PHP (seperti komponen Laravel Eloquent ORM dan database validator):</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Terminal</span>
                        <span>Composer</span>
                    </div>
                    <pre><code># Instalasi library PHP eksternal
composer install</code></pre>
                </div>

                <h3>2. Konfigurasi Environment (.env)</h3>
                <p>Salin file template <span class="highlight-text">.env.example</span> menjadi <span class="highlight-text">.env</span> pada root direktori proyek, kemudian sesuaikan parameter koneksi database Anda:</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Properties</span>
                        <span>.env</span>
                    </div>
                    <pre><code>DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=

# Set true untuk development, false untuk production
APP_DEBUG=true</code></pre>
                </div>

                <h3>3. Migrasi & Seeding Database</h3>
                <p>Buat database baru di DBMS Anda (misal MySQL) sesuai nama di <code>DB_DATABASE</code>, lalu jalankan perintah CLI Aero untuk mengisi skema tabel dan data awal:</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Terminal</span>
                        <span>Aero CLI</span>
                    </div>
                    <pre><code># A. Buat struktur tabel database bawaan
php aero migrate

# B. Isi tabel dengan data seeder awal (opsional)
php aero seed</code></pre>
                </div>

                <h3>4. Menjalankan Server Lokal</h3>
                <p>Mulai server web pengembangan lokal Aero Anda dengan menggunakan built-in CLI server:</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Terminal</span>
                        <span>Aero CLI</span>
                    </div>
                    <pre><code># Server akan aktif di http://localhost:8000
php aero serve</code></pre>
                </div>
            </section>

            <!-- Section: Database & ORM -->
            <section id="database" class="doc-section">
                <h2>Database & ORM Setup</h2>
                <p>AeroAPI Framework secara dinamis mendeteksi database driver Anda melalui parameter DB_DSN di file .env. Framework mendukung MySQL, SQLite, dan PostgreSQL secara native dengan penanganan path SQLite terisolasi.</p>
                
                <table>
                    <thead>
                        <tr>
                            <th>Database</th>
                            <th>Konfigurasi DSN di <code>.env</code></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>SQLite</strong></td>
                            <td><span class="highlight-text">sqlite:database/database.sqlite</span> <br><small style="color: var(--text-muted)">*Direktori database & file sqlite otomatis dibuat jika tidak ada.</small></td>
                        </tr>
                        <tr>
                            <td><strong>MySQL</strong></td>
                            <td><span class="highlight-text">mysql:host=127.0.0.1;port=3306;dbname=aero_db</span></td>
                        </tr>
                        <tr>
                            <td><strong>PostgreSQL</strong></td>
                            <td><span class="highlight-text">pgsql:host=127.0.0.1;port=5432;dbname=aero_db</span></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Section: Models & CRUD -->
            <section id="models" class="doc-section">
                <h2>Models & Eloquent CRUD</h2>
                <p>Seluruh model Anda ditempatkan di direktori app/Models/ dan mewarisi fungsionalitas Eloquent ORM. Anda bisa langsung melakukan manipulasi data dengan sangat intuitif.</p>

                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Model</span>
                        <span>app/Models/User.php</span>
                    </div>
                    <pre><code>&lt;?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    public $timestamps = false;
}</code></pre>
                </div>

                <h3>Contoh Query Eloquent:</h3>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP CRUD</span>
                        <span>Controller / Router</span>
                    </div>
                    <pre><code>use App\Models\User;

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

// 4. Delete Data
User::destroy(2);</code></pre>
                </div>
            </section>

            <!-- Section: Routing & Controller -->
            <section id="routing" class="doc-section">
                <h2>Routing & Controller</h2>
                <p>AeroAPI memiliki sistem routing elegan di folder routes/web.php untuk memetakan URL langsung ke method Controller Anda.</p>

                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Web Routing</span>
                        <span>routes/web.php</span>
                    </div>
                    <pre><code>&lt;?php

use Core\Route;
use App\Controllers\HomeController;
use App\Controllers\ApiController;

// A. Standard Web & API Routing
Route::get('/', [HomeController::class, 'index']);
Route::post('/api/submit', [HomeController::class, 'submitData']);

// B. RESTful API Routing (GET, POST, PUT, DELETE)
Route::get('/api/items', [ApiController::class, 'getItems']);
Route::get('/api/items/detail', [ApiController::class, 'getItemDetail']);
Route::post('/api/items', [ApiController::class, 'createItem']);
Route::put('/api/items', [ApiController::class, 'updateItem']);
Route::delete('/api/items', [ApiController::class, 'deleteItem']);</code></pre>
                </div>

                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Controller</span>
                        <span>app/Controllers/HomeController.php</span>
                    </div>
                    <pre><code>&lt;?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'users' => User::all()
        ]);
    }
}</code></pre>
                </div>

                <h3>3. Route Grouping (Grup Routing)</h3>
                <p>Aero mendukung pengelompokan route untuk berbagi prefix URL maupun middleware yang sama menggunakan <span class="highlight-text">Route::group()</span>:</p>

                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Routing</span>
                        <span>routes/web.php</span>
                    </div>
                    <pre><code>use Core\Route;
use App\Controllers\UserController;
use App\Middlewares\AuthMiddleware;

// Semua route di dalam grup ini otomatis memiliki prefix /api dan melewati AuthMiddleware
Route::group(['prefix' => '/api', 'middleware' => AuthMiddleware::class], function() {
    
    // URL: /api/users
    Route::get('/users', [UserController::class, 'index']);
    
    // URL: /api/profile
    Route::get('/profile', [UserController::class, 'profile']);
});</code></pre>
                </div>
            </section>

            <!-- Section: Services & IoC -->
            <section id="services" class="doc-section">
                <h2>Services & IoC Container</h2>
                <p>AeroAPI dilengkapi dengan <strong>IoC (Inversion of Control) Service Container</strong> yang powerful untuk mengelola dependensi class dan melakukan **Dependency Injection** otomatis melalui constructor (*autowiring*).</p>

                <h3>1. Membuat Class Service</h3>
                <p>Service berisi logika bisnis spesifik untuk memisahkan tanggung jawab dari Controller. Buat direktori <span class="highlight-text">app/Services/</span> dan tambahkan class service Anda:</p>

                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Service</span>
                        <span>app/Services/UserService.php</span>
                    </div>
                    <pre><code>&lt;?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getActiveUsersCount(): int
    {
        return User::count();
    }
}</code></pre>
                </div>

                <h3>2. Dependency Injection Otomatis (Autowiring)</h3>
                <p>Anda hanya perlu menuliskan type-hint class Service pada constructor Controller Anda. Aero Container akan menganalisis dependensi tersebut menggunakan PHP Reflection dan menyuntikkannya (*inject*) secara otomatis!</p>

                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Controller</span>
                        <span>app/Controllers/UserController.php</span>
                    </div>
                    <pre><code>&lt;?php

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
        
        return response()->json([
            'status' => 'success',
            'user_count' => $count
        ]);
    }
}</code></pre>
                </div>

                <h3>3. Mendaftarkan Binding / Singleton</h3>
                <p>Anda juga dapat mendaftarkan binding kustom atau instansi tunggal (*singleton*) ke dalam Container secara terpusat:</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Binding</span>
                        <span>public/index.php / bootstrap</span>
                    </div>
                    <pre><code>// Bind instansi dengan Closure resolver
app()->bind('payment.gateway', function() {
    return new \App\Services\StripePaymentGateway();
});

// Daftarkan sebagai Singleton
app()->singleton(\App\Services\ConfigService::class, function() {
    return new \App\Services\ConfigService($_ENV);
});

// Mengambil instansi dari container di bagian kode mana saja
$config = app()->make(\App\Services\ConfigService::class);</code></pre>
                </div>
            </section>

            <!-- Section: Middleware -->
            <section id="middleware" class="doc-section">
                <h2>Middleware</h2>
                <p>Middleware menyediakan mekanisme terpusat untuk memfilter HTTP request yang masuk ke aplikasi Anda (misalnya verifikasi otentikasi JWT/Token, Session, atau CORS).</p>

                <h3>1. Membuat Middleware</h3>
                <p>Setiap middleware di Aero dibuat sebagai class di direktori <span class="highlight-text">app/Middlewares/</span> dan meng-extend class <span class="highlight-text">Core\Middleware</span>.</p>

                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Middleware</span>
                        <span>app/Middlewares/AuthMiddleware.php</span>
                    </div>
                    <pre><code>&lt;?php

namespace App\Middlewares;

use Core\Middleware;

class AuthMiddleware extends Middleware
{
    public function execute()
    {
        $headers = request()->getHeaders();
        $token = $headers['Authorization'] ?? null;
        
        if (!$token || $token !== 'Bearer rahasia123') {
            abort(401, 'Unauthorized. Akses ditolak oleh Middleware.');
        }
    }
}</code></pre>
                </div>

                <h3>2. Mendaftarkan Middleware</h3>
                <p>Aero mendukung dua metode pendaftaran middleware yang fleksibel:</p>

                <h4>Metode A: Route-Level (Di routes/web.php)</h4>
                <p>Anda dapat mendaftarkan middleware langsung saat mendefinisikan route secara chained:</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Routing</span>
                        <span>routes/web.php</span>
                    </div>
                    <pre><code>use Core\Route;

Route::get('/api/users', [\App\Controllers\UserController::class, 'index'])
    ->middleware(\App\Middlewares\AuthMiddleware::class);</code></pre>
                </div>

                <h4>Metode B: Controller-Level (Di Controller Constructor)</h4>
                <p>Anda juga dapat mendaftarkan middleware di dalam constructor Controller untuk membatasi akses pada action tertentu:</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Controller</span>
                        <span>app/Controllers/UserController.php</span>
                    </div>
                    <pre><code>class UserController extends Controller
{
    public function __construct()
    {
        // Hanya jalankan AuthMiddleware pada action 'index'
        $this->registerMiddleware(new AuthMiddleware(['index']));
    }
}</code></pre>
                </div>
            </section>

            <!-- Section: Debugging -->
            <section id="debugging" class="doc-section">
                <h2>Error Handling & Debugging</h2>
                <p>AeroAPI dilengkapi dengan sistem **Global Exception & Error Handler** yang sangat responsif terhadap parameter konfigurasi <span class="highlight-text">APP_DEBUG</span> di file <span class="highlight-text">.env</span> Anda.</p>

                <h3>1. Pengaturan di .env</h3>
                <p>Anda dapat mengontrol keluaran detail error dengan menyetel variabel <span class="highlight-text">APP_DEBUG</span>:</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Configuration</span>
                        <span>.env</span>
                    </div>
                    <pre><code># Aktifkan untuk melihat error interaktif selama masa pengembangan (development)
APP_DEBUG=true

# Matikan saat aplikasi sudah online di server (production) untuk keamanan
# APP_DEBUG=false</code></pre>
                </div>

                <h3>2. Mode Debug Aktif (APP_DEBUG=true)</h3>
                <p>Ketika terjadi error koding atau query database bermasalah, AeroAPI akan menghentikan eksekusi dan merender **AeroAPI Debug Page** yang berisi:</p>
                <ul>
                    <li>Nama lengkap Exception Class yang terjadi.</li>
                    <li>Pesan kesalahan (*error message*) yang mendetail.</li>
                    <li>Lokasi file absolut beserta baris tepat terjadinya error.</li>
                    <li>Monospace **Stack Trace** lengkap untuk memudahkan pelacakan error.</li>
                </ul>

                <h3>3. Mode Produksi (APP_DEBUG=false)</h3>
                <p>Saat mode debug dimatikan, AeroAPI akan mengamankan aplikasi dengan menyembunyikan detail teknis (seperti database login atau file path server) dari publik:</p>
                <ul>
                    <li><strong>Untuk Web Request</strong>: Merender halaman error yang ramah pengguna (ditampilkan dari berkas <span class="highlight-text">app/Views/error.php</span>).</li>
                    <li><strong>Untuk API Request (JSON)</strong>: Mengembalikan respons JSON standar yang aman:
                        <div class="code-container" style="margin-top: 10px;">
                            <div class="code-header">
                                <span class="code-lang">JSON</span>
                                <span>API Error Response</span>
                            </div>
                            <pre><code>{
    "status": "error",
    "code": 500,
    "message": "Terjadi kesalahan internal server."
}</code></pre>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- Section: API Benchmarking -->
            <section id="benchmarking" class="doc-section">
                <h2>API Benchmarking Tool</h2>
                <p>AeroAPI dilengkapi dengan <strong>High-Precision API Benchmarking & Speed Tester</strong> bawaan berbasis CLI dan Web yang memungkinkan Anda melakukan stres-tes serta mengukur performa API secara real-time.</p>

                <h3>1. Cara Kerja & Fitur Utama</h3>
                <ul>
                    <li><strong>AERO_START High-Precision Tracking</strong>: Waktu pemrosesan internal server dihitung dalam skala mikrodetik dari sejak request pertama kali menyentuh server.</li>
                    <li><strong>X-Execution-Time Header</strong>: Setiap respons web maupun JSON API secara otomatis menyertakan header kustom berisi durasi pemrosesan server murni.</li>
                    <li><strong>Debug Metadata Injection</strong>: Saat <span class="highlight-text">APP_DEBUG=true</span>, respons JSON dari controller akan secara dinamis menyertakan blok meta <span class="highlight-text">_debug</span> berisi total execution time.</li>
                </ul>

                <h3>2. Menulis Berkas Skenario Test API</h3>
                <p>Seluruh berkas pengujian diletakkan di dalam folder <span class="highlight-text">tests/api/</span> dengan format berkas berekstensi <span class="highlight-text">.php</span> yang mengembalikan array konfigurasi pengujian:</p>

                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Test Config</span>
                        <span>tests/api/benchmark_post.php</span>
                    </div>
                    <pre><code>&lt;?php

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
    'requests' => 10, // Jumlah request stres-tes yang dikirim
];</code></pre>
                </div>

                <h3>3. Eksekusi Uji Kecepatan</h3>
                <p>Jalankan perintah benchmark di terminal CLI Anda:</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Terminal</span>
                        <span>CLI</span>
                    </div>
                    <pre><code># A. Memunculkan Menu Pilihan Interaktif berkas pengujian (0-7)
php aero benchmark

# B. Menjalankan secara langsung berkas pengujian nomor indeks 3 (bypass menu)
php aero benchmark 3

# C. Menjalankan berkas pengujian spesifik berdasarkan nama berkas
php aero benchmark benchmark_users.php</code></pre>
                </div>
            </section>

            <!-- Section: CLI -->
            <section id="cli" class="doc-section">
                <h2>CLI Commands</h2>
                <p>Kelola skema dan isi database serta jalankan web server pengembangan langsung menggunakan AeroAPI CLI.</p>

                <h3>1. Perintah CLI Bawaan</h3>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Terminal Commands</span>
                        <span>CLI</span>
                    </div>
                    <pre><code># A. Jalankan Migrasi Database
php aero migrate

# B. Isi database dengan data palsu dari folder seeders/
php aero seed

# C. Jalankan development server di port default (8000)
php aero serve

# D. Jalankan development server di port kustom (misal: 8080)
php aero serve 8080
# atau
php aero serve --port=8080

# E. Jalankan Benchmarking Kecepatan API (Akan memunculkan Menu Pilihan Interaktif 0-7)
php aero benchmark

# F. Jalankan berkas benchmark secara langsung berdasarkan nomor indeks pilihan menu (bypass menu)
php aero benchmark 3

# G. Jalankan berkas benchmark spesifik menggunakan nama berkasnya
php aero benchmark benchmark_users.php</code></pre>
                </div>

                <h3>2. Membuat Perintah Kustom</h3>
                <p>AeroAPI memiliki sistem <strong>Auto-Discovery</strong> untuk CLI command. Cukup buat file class baru di direktori <span class="highlight-text">app/Commands/</span> dengan nama file berakhiran <span class="highlight-text">Command.php</span>, maka perintah tersebut langsung terdaftar secara dinamis!</p>

                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">PHP Command</span>
                        <span>app/Commands/HelloCommand.php</span>
                    </div>
                    <pre><code>&lt;?php

namespace App\Commands;

use Core\Command;

class HelloCommand extends Command
{
    /**
     * Eksekusi logika CLI
     * 
     * @param array $args Seluruh argumen dari terminal ($argv)
     */
    public function execute(array $args)
    {
        // Ambil argumen ke-3 (indeks 2) sebagai parameter nama
        $name = $args[2] ?? 'Dunia';
        
        // Log ke konsol dengan format: [Y-m-d H:i:s] - Message
        $this->log("Halo, " . $name . "!");
    }
}</code></pre>
                </div>

                <p>Jalankan perintah kustom Anda melalui terminal:</p>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-lang">Terminal</span>
                        <span>CLI</span>
                    </div>
                    <pre><code># Output default: [2026-05-17 09:05:00] - Halo, Dunia!
php aero hello

# Dengan argumen tambahan: [2026-05-17 09:05:00] - Halo, Dodhy!
php aero hello Dodhy</code></pre>
                </div>
            </section>

        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 Aero Framework. Dibuat untuk performa ekstrim dan kemudahan pengembangan.</p>
    </footer>

    <!-- Simple Active Navigation Highlight Script -->
    <script>
        const sections = document.querySelectorAll(".doc-section");
        const navLinks = document.querySelectorAll(".nav-links a");

        window.addEventListener("scroll", () => {
            let current = "";
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= (sectionTop - 150)) {
                    current = section.getAttribute("id");
                }
            });

            navLinks.forEach(link => {
                link.classList.remove("active");
                if (link.getAttribute("href") === `#${current}`) {
                    link.classList.add("active");
                }
            });
        });

        // Interactive API Tester Script
        function testApi(url, method, headers = {}) {
            const outputEl = document.getElementById('api-output');
            const statusEl = document.getElementById('api-status');
            const latencyEl = document.getElementById('api-latency');
            
            outputEl.textContent = `Mengirim permintaan ${method} ke ${url}...`;
            statusEl.textContent = 'PENDING';
            statusEl.className = 'badge badge-info';
            latencyEl.style.display = 'none';
            
            const startTime = performance.now();
            
            const options = {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...headers
                }
            };
            
            if (method === 'POST') {
                options.body = JSON.stringify({ name: 'Aero Tester', message: 'Hello from API Console!' });
            }
            
            fetch(url, options)
                .then(async response => {
                    const duration = (performance.now() - startTime).toFixed(1);
                    const serverTime = response.headers.get('x-execution-time') || '0.0ms';
                    latencyEl.textContent = `(Network: ${duration} ms, Server: ${serverTime})`;
                    latencyEl.style.display = 'inline';
                    
                    const contentType = response.headers.get('content-type');
                    statusEl.textContent = `${response.status} ${response.statusText}`;
                    if (response.ok) {
                        statusEl.className = 'badge badge-success';
                    } else {
                        statusEl.className = 'badge badge-danger';
                    }
                    
                    if (contentType && contentType.includes('application/json')) {
                        const json = await response.json();
                        outputEl.textContent = JSON.stringify(json, null, 4);
                    } else {
                        const text = await response.text();
                        if (text.includes('<!DOCTYPE html>') || text.includes('<html>')) {
                            outputEl.textContent = `[HTML Document Response (Previews/Slices)]\n\n${text.substring(0, 1000)}...`;
                        } else {
                            outputEl.textContent = text;
                        }
                    }
                })
                .catch(error => {
                    const duration = (performance.now() - startTime).toFixed(1);
                    latencyEl.textContent = `(Network: ${duration} ms, Server: ERROR)`;
                    latencyEl.style.display = 'inline';
                    
                    statusEl.textContent = 'ERROR';
                    statusEl.className = 'badge badge-danger';
                    outputEl.textContent = `Error: ${error.message}`;
                });
        }
    </script>
</body>
</html>
