<?php

return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'travaux_db',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ],
    'app' => [
        'name' => 'Travaux Pro',
        'url' => 'http://localhost',
        'debug' => true,
        'timezone' => 'Europe/Paris'
    ],
    'session' => [
        'lifetime' => 7200,
        'secure' => false,
        'httponly' => true
    ],
    'upload' => [
        'max_size' => 5242880, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
        'projects_path' => __DIR__ . '/../../public/uploads/projects/',
        'profiles_path' => __DIR__ . '/../../public/uploads/profiles/'
    ]
];
