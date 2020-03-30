<?php


namespace App\src;

use App\src\http\Request;
use App\src\http\Response;

/**
 * Controller abstract class
 *
 * @package App\src
 */
abstract class ControllerAbstract
{
    /**
     * Controller's id
     *
     * @var string
     */
    protected $id;

    /**
     * Request instance
     *
     * @var Request
     */
    protected Request $request;

    /**
     * Response instance
     *
     * @var Response
     */
    protected Response $response;

    /**
     * View instance
     *
     * @var View
     */
    protected View $view;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->response = new Response();
        $this->request = Application::app()->getRequest();
        $this->view = new View($this->response);
    }

    /**
     * Default implementation for controller's run
     */
    public function run()
    {
        $view = strtolower(substr(basename(get_called_class()), 0, -10));
        $this->render($view);
    }

    /**
     * Returns controller's id
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Renders specified view
     *
     * @param string $view
     * @param array|null $params
     */
    public function render(string $view, array $params = null) : void
    {
        $buffer = $this->view->render($view, $params);
        $this->response->setContent($buffer);
        $this->response->send();
    }

    /**
     * Sends response to AJAX request
     *
     * @param $content
     */
    public function sendAjax($content) : void
    {
        $this->response->setContent(json_encode($content));
        $this->response->send(true);
    }

    /**
     * Returns Response instance
     *
     * @return Response
     */
    public function getResponse() : Response
    {
        return $this->response;
    }
}