<?php

use App\controllers\TaskController;
use App\models\Administrator;
use App\src\filters\AuthFilter;

/**
 * Application config
 *
 * 'app-id' - indentificator of application
 * 'controllersNamespace' - namespace of application's controllers
 * 'migrationPath' - path for sql-migrations
 * 'defaultController' - controller class used by default
 * 'userClass' - user's model class
 * 'filters' - application access filters config. Actions that are not allowed should be mentioned in 'permissions' section
 */
return [
    'app-id' => 'beejee-test',
    'controllersNamespace' => 'App\\controllers\\',
    'migrationsPath' => __DIR__ . '\\..\\console\\migrations',
    'defaultController' => TaskController::class,
    'userClass' => Administrator::class,
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