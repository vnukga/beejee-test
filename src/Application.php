<?php

namespace App\src;

use App\src\database\Config;
use App\src\database\Connection;
use App\src\http\Request;

class Application
{
    private array $config;

    private string $controllersNamespace;

    private Connection $connection;

    private Request $request;

    private ControllerAbstract $controller;


    public function __construct(array $config)
    {
        $this->config = $config;
        $this->controllersNamespace = $config['controllersNamespace'];
        $dbConfig = $this->config['db'];
        $this->connection = new Connection(new Config($dbConfig));
        $this->request = new Request();
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