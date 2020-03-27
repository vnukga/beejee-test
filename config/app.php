<?php

use App\src\filters\AuthFilter;

return [
    'app-id' => 'beejee-test',
    'controllersNamespace' => 'App\\controllers\\',
    'migrationsPath' => __DIR__ . '\\..\\console\\migrations',
    'filters' => [
        'authFilter' => [
            'class' => AuthFilter::class,
            'permissions' => [
                'user' => [
                    'login'
                ]
            ]
        ]
    ]
];