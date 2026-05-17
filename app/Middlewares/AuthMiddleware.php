<?php

namespace App\Middlewares;

use Core\Middleware;

class AuthMiddleware extends Middleware
{
    public function execute()
    {
        // Cek contoh token untuk API, atau bisa menggunakan Session untuk Web
        $headers = request()->getHeaders();
        $token = $headers['Authorization'] ?? null;
        
        // Simulasikan pengecekan token/login
        if (!$token || $token !== 'Bearer rahasia123') {
            abort(401, 'Unauthorized. Akses ditolak oleh Middleware (token tidak valid).');
        }
    }
}
