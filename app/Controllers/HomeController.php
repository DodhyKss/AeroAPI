<?php

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $dbDriver = 'None';
        $dbStatus = 'Disconnected';
        $userCount = 0;
        try {
            $pdo = \Core\Application::$app->db->pdo;
            if ($pdo) {
                $dbDriver = strtoupper($pdo->getAttribute(\PDO::ATTR_DRIVER_NAME));
                $dbStatus = 'Connected';
                
                // Cek apakah tabel users ada
                if (\Illuminate\Database\Capsule\Manager::schema()->hasTable('users')) {
                    $userCount = \App\Models\User::count();
                }
            }
        } catch (\Exception $e) {
            $dbStatus = 'Error (DB offline)';
        }

        return view('home', [
            'name' => 'Aero PHP Framework',
            'dbDriver' => $dbDriver,
            'dbStatus' => $dbStatus,
            'userCount' => $userCount
        ]);
    }

    public function about()
    {
        return view('home', [
            'name' => 'Halaman About'
        ]);
    }

    public function apiData()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil dari framework baru Anda!',
            'data' => [1, 2, 3, 4, 5]
        ]);
    }

    public function submitData()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diterima',
            'received' => request()->getBody()
        ]);
    }
}
