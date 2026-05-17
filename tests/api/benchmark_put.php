<?php

return [
    'name' => 'REST PUT - Perbarui Item (ID 1)',
    'url' => 'http://localhost:8000/api/items?id=1',
    'method' => 'PUT',
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
    'body' => json_encode([
        'title' => 'Judul Item Diperbarui!',
        'body' => 'Data konten item berhasil dimodifikasi melalui method PUT.'
    ]),
    'requests' => 10,
];
