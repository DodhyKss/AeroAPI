<?php

return [
    'name' => 'REST DELETE - Hapus Item (ID 3)',
    'url' => 'http://localhost:8000/api/items?id=3',
    'method' => 'DELETE',
    'headers' => [
        'Accept' => 'application/json',
    ],
    'requests' => 10,
];
