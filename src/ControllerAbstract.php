<?php


namespace App\src;


use App\src\http\Request;
use App\src\http\Response;

abstract class ControllerAbstract
{
    protected $id;

    protected Request $request;

    protected Response $response;

    protected View $view;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->response = new Response();
        $this->request = Application::app()->getRequest();
        $this->view = new View();
    }

    public function run()
    {
        $view = strtolower(substr(basename(get_called_class()), 0, -10));
        $this->render('index');
    }

    public function getId()
    {
        return $this->id;
    }

    public function render(string $view, array $params = null)
    {
        $buffer = $this->view->render($view, $params);
        $this->response->setContent($buffer);
        $this->response->send();
    }

    public function getResponse()
    {
        return $this->response;
    }
}