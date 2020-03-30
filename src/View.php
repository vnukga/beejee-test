<?php


namespace App\src;


use App\src\http\Response;

class View
{
    protected string $viewPath;

    protected string $layoutPath;

    protected string $flashPath;

    private Response $response;

    public function __construct(Response $response)
    {
        $this->viewPath = __DIR__ . '\\..\\views\\';
        $this->layoutPath = $this->viewPath . 'layouts\\main.php';
        $this->flashPath = $this->viewPath . 'flash-message.php';
        $this->response = $response;
    }

    public function render(string $view, array $params = null) : ?string
    {
        if($params) {
            extract($params);
        }
        $content = $this->viewPath . $view . '.php';
        $message = $this->getFlashMessage();
        ob_start();
        include $this->layoutPath;
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    private function getFlashMessage()
    {
        $message = $this->response->getFlash();
        if($message) {
            $this->response->clearFlash();
        }
        return $message;
    }
}