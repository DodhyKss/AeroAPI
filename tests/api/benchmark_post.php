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
        'title' => 'REST API Baru dari Benchmark',
        'body' => 'Menguji endpoint POST yang menerima payload data JSON secara real-time.'
    ]),
    'requests' => 10,
];
