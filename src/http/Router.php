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

/**
 * Class Router
 * @package App\src\http
 */
class Router
{
    /**
     * Application's route
     *
     * @var string|null
     */
    private string $route;

    /**
     * Request instance
     *
     * @var Request
     */
    private Request $request;

    /**
     * Controller's instance
     *
     * @var ControllerAbstract
     */
    private ControllerAbstract $controller;

    public function __construct()
    {
        $this->request = new Request();
        $this->route = $this->request->getRoute() ?? 'index';

    }

    /**
     * Handles request
     */
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

    /**
     * Filters request
     *
     * @return bool
     * @throws NotAuthorizedException
     */
    private function filter() : bool
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
     * Returns filters list
     *
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

    /**
     * Returns Request instance
     *
     * @return Request
     */
    public function getRequest() : Request
    {
        return $this->request;
    }

    /**
     * Sets controller for application's route
     *
     * @throws ControllerNotFoundException
     */
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

    /**
     * Returns controller's name from application's route
     *
     * @return string
     */
    private function getControllerNameFromRoute() : string
    {
        $controllerName = implode('', explode( '-', ucwords($this->route, '-')));
        $controllerName .= 'Controller';
        $controllerName = Application::app()->getConfigParameter('controllersNamespace') . $controllerName;
        return $controllerName;
    }
}