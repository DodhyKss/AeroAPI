<?php

use Core\Route;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Middlewares\AuthMiddleware;

use App\Controllers\ApiController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/api/data', [HomeController::class, 'apiData']);
Route::post('/api/submit', [HomeController::class, 'submitData'])->middleware(AuthMiddleware::class);

// RESTful API Contoh CRUD Bawaan Aero (GET, POST, PUT, DELETE)
Route::get('/api/items', [ApiController::class, 'getItems']);
Route::get('/api/items/detail', [ApiController::class, 'getItemDetail']);
Route::post('/api/items', [ApiController::class, 'createItem']);
Route::put('/api/items', [ApiController::class, 'updateItem']);
Route::delete('/api/items', [ApiController::class, 'deleteItem']);




