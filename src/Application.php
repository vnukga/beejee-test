<?php

namespace App\src;

use App\src\database\Config;
use App\src\database\Connection;
use App\src\database\Migration;
use App\src\http\Request;

class Application
{
    private static $instance;

    private array $config;

    private string $controllersNamespace;

    private Connection $connection;

    private Request $request;

    private ControllerAbstract $controller;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->controllersNamespace = $config['controllersNamespace'];
        $dbConfig = $this->config['db'];
        $this->connection = new Connection(new Config($dbConfig));
        $this->request = new Request();
    }

    public static function init(array $config)
    {
        if(self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public static function app() : self
    {
        return self::$instance;
    }

    public function run()
    {
        $route = $this->request->getRoute();
        $this->setControllerFromRoute($route);
        return $this->controller->run();
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getMigrations() : array
    {
        $migrationsPath = $this->config['migrationsPath'];
        $migrationList = [];
        foreach (scandir($migrationsPath) as $migration){
            if($migration === '.' || $migration === '..') {
                continue;
            }
            $fileName = $migrationsPath . DIRECTORY_SEPARATOR . $migration;
            $migrationList[] = new Migration($fileName, $this->connection);
        }
        return $migrationList;
    }

    private function setControllerFromRoute(string $route) : void
    {
        if(strlen($route) > 0) {
            $controllerName = $this->getControllerNameFromRoute($route);
            $this->controller = new $controllerName($route);
        }
    }

    private function getControllerNameFromRoute(string $route)
    {
        $controllerName = implode('', explode( '-', ucwords($route, '-')));
        $controllerName .= 'Controller';
        $controllerName = $this->controllersNamespace . $controllerName;
        return $controllerName;
    }
}