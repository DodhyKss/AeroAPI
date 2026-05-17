<?php

namespace App\Controllers;

use Core\Controller;
use Core\Request;
use Core\Response;

class ApiController extends Controller
{
    // Database simulasi in-memory untuk demo RESTful API
    private static array $items = [
        1 => ['id' => 1, 'title' => 'Belajar Aero Framework', 'body' => 'Aero sangat cepat dan minimalis.'],
        2 => ['id' => 2, 'title' => 'Laravel Eloquent ORM', 'body' => 'Terintegrasi penuh dengan Aero.'],
        3 => ['id' => 3, 'title' => 'RESTful API Bawaan', 'body' => 'Mendukung penuh GET, POST, PUT, DELETE.']
    ];

    /**
     * 1. GET /api/items
     * Mengambil semua data item
     */
    public function getItems(Request $request, Response $response)
    {
        return $response->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil seluruh item.',
            'data' => array_values(self::$items)
        ]);
    }

    /**
     * 2. GET /api/items/detail
     * Mengambil detail item tertentu (contoh URL: /api/items/detail?id=1)
     */
    public function getItemDetail(Request $request, Response $response)
    {
        $body = $request->getBody();
        $id = isset($body['id']) ? (int)$body['id'] : 0;

        if (isset(self::$items[$id])) {
            return $response->json([
                'status' => 'success',
                'message' => 'Berhasil menemukan detail item.',
                'data' => self::$items[$id]
            ]);
        }

        return $response->json([
            'status' => 'error',
            'message' => "Item dengan ID $id tidak ditemukan!"
        ], 404);
    }

    /**
     * 3. POST /api/items
     * Menambahkan item baru (menerima Payload JSON)
     */
    public function createItem(Request $request, Response $response)
    {
        $body = $request->getBody();
        
        if (empty($body['title']) || empty($body['body'])) {
            return $response->json([
                'status' => 'error',
                'message' => 'Input title dan body wajib disertakan!'
            ], 400);
        }

        $newId = count(self::$items) > 0 ? max(array_keys(self::$items)) + 1 : 1;
        $newItem = [
            'id' => $newId,
            'title' => $body['title'],
            'body' => $body['body']
        ];

        // Simpan ke database simulasi memori
        self::$items[$newId] = $newItem;

        return $response->json([
            'status' => 'success',
            'message' => 'Item baru berhasil ditambahkan!',
            'data' => $newItem
        ], 201); // 201 Created
    }

    /**
     * 4. PUT /api/items
     * Memperbarui item yang sudah ada (menerima Payload JSON, contoh URL: /api/items?id=1)
     */
    public function updateItem(Request $request, Response $response)
    {
        $body = $request->getBody();
        $id = isset($body['id']) ? (int)$body['id'] : 0;

        if (!isset(self::$items[$id])) {
            return $response->json([
                'status' => 'error',
                'message' => "Item dengan ID $id tidak ditemukan untuk diperbarui!"
            ], 404);
        }

        if (isset($body['title'])) {
            self::$items[$id]['title'] = $body['title'];
        }
        if (isset($body['body'])) {
            self::$items[$id]['body'] = $body['body'];
        }

        return $response->json([
            'status' => 'success',
            'message' => "Item dengan ID $id berhasil diperbarui!",
            'data' => self::$items[$id]
        ]);
    }

    /**
     * 5. DELETE /api/items
     * Menghapus item tertentu (contoh URL: /api/items?id=1)
     */
    public function deleteItem(Request $request, Response $response)
    {
        $body = $request->getBody();
        $id = isset($body['id']) ? (int)$body['id'] : 0;

        if (!isset(self::$items[$id])) {
            return $response->json([
                'status' => 'success',
                'message' => "Item dengan ID $id sudah tidak ada (idempotent delete success)!"
            ]);
        }

        $deletedItem = self::$items[$id];
        unset(self::$items[$id]);

        return $response->json([
            'status' => 'success',
            'message' => "Item dengan ID $id berhasil dihapus secara permanen!",
            'data' => $deletedItem
        ]);
    }
}
