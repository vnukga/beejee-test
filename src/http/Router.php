<?php
declare(strict_types=1);

namespace App\src\http;


use App\controllers\ForbiddenController;
use App\controllers\PageNotFoundController;
use App\src\Application;
use App\src\ControllerAbstract;
use App\src\Exceptions\ControllerNotFoundException;
use App\src\Exceptions\NotAuthorizedException;
use App\src\filters\FilterInterface;

class Router
{
    private string $route;

    private Request $request;

    private ControllerAbstract $controller;

    public function __construct()
    {
        $this->request = new Request();
        $this->route = $this->request->getRoute() ?? 'index';

    }

    public function handle() : void
    {
        try {
            $this->filter();
            $this->setControllerFromRoute();
        }
        catch (NotAuthorizedException $exception) {
            $this->controller = new ForbiddenController('forbidden');
        }
        catch (ControllerNotFoundException $exception) {
            $this->controller = new PageNotFoundController('page-not-found');
        }
        $this->controller->run();
    }

    private function filter()
    {
        $filters = $this->getFilters();
        foreach ($filters as $filter) {
            if(!$filter->run($this->route)) {
                throw new NotAuthorizedException();
            }
        }
        return true;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters() : array
    {
        $filters = [];
        foreach (Application::app()->getConfigParameter('filters') as $filter){
            $filters[] = new $filter['class']($filter);
        }
        return $filters;
    }

    public function getRequest() : Request
    {
        return $this->request;
    }

    private function setControllerFromRoute() : void
    {
        if(strlen($this->route) > 0) {
            $controllerName = $this->getControllerNameFromRoute();
            if(!class_exists($controllerName)) {
                throw new ControllerNotFoundException();
            }
            $this->controller = new $controllerName($this->route);
        }
    }

    private function getControllerNameFromRoute() : string
    {
        $controllerName = implode('', explode( '-', ucwords($this->route, '-')));
        $controllerName .= 'Controller';
        $controllerName = Application::app()->getConfigParameter('controllersNamespace') . $controllerName;
        return $controllerName;
    }
}