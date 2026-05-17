<?php

return [
    'name' => 'Benchmark GET API Data (Public Response)',
    'url' => 'http://localhost:8000/api/data',
    'method' => 'GET',
    'headers' => [
        'Accept' => 'application/json',
    ],
    'requests' => 30,
];