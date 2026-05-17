<?php

return [
    'name' => 'Benchmark GET Users API (Eloquent DB Fetch)',
    'url' => 'http://localhost:8000/users',
    'method' => 'GET',
    'headers' => [
        'Accept' => 'application/json',
    ],
    'requests' => 20,
];