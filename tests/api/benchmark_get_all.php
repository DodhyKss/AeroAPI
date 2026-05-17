<?php

return [
    'name' => 'REST GET - Ambil Semua Item',
    'url' => 'http://localhost:8000/api/items',
    'method' => 'GET',
    'headers' => [
        'Accept' => 'application/json',
    ],
    'requests' => 15,
];
