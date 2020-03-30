<?php


namespace App\src;

use App\src\http\Response;

/**
 * Class View
 * @package App\src
 */
class View
{
    /**
     * Path to view's file
     *
     * @var string
     */
    protected string $viewPath;

    /**
     * Path to layout's file
     *
     * @var string
     */
    protected string $layoutPath;

    /**
     * Path to flash-message file
     *
     * @var string
     */
    protected string $flashPath;

    /**
     * Response instance
     *
     * @var Response
     */
    private Response $response;

    public function __construct(Response $response)
    {
        $this->viewPath = __DIR__ . '\\..\\views\\';
        $this->layoutPath = $this->viewPath . 'layouts\\main.php';
        $this->flashPath = $this->viewPath . 'flash-message.php';
        $this->response = $response;
    }

    /**
     * Renders view from file
     *
     * @param string $view
     * @param array|null $params
     * @return string|null
     */
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

    /**
     * Returns flash-message if exists
     *
     * @return array|null
     */
    private function getFlashMessage()
    {
        $message = $this->response->getFlash();
        if($message) {
            $this->response->clearFlash();
        }
        return $message;
    }
}