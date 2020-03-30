<?php

use App\controllers\TaskController;
use App\src\filters\AuthFilter;

return [
    'app-id' => 'beejee-test',
    'controllersNamespace' => 'App\\controllers\\',
    'migrationsPath' => __DIR__ . '\\..\\console\\migrations',
    'defaultController' => TaskController::class,
    'filters' => [
        'authFilter' => [
            'class' => AuthFilter::class,
            'permissions' => [
                'user' => [
                    'login'
                ],
                'guest' => [
                    'task-close',
                    'task-update'
                ]
            ]
        ]
    ]
];