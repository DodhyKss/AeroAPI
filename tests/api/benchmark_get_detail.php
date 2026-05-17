<?php

return [
    'name' => 'REST GET - Detail Item (ID 1)',
    'url' => 'http://localhost:8000/api/items/detail?id=1',
    'method' => 'GET',
    'headers' => [
        'Accept' => 'application/json',
    ],
    'requests' => 15,
];
