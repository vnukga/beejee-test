<?php


namespace App\src;


class View
{
    protected string $viewPath;

    protected string $layoutPath;

    public function __construct()
    {
        $this->viewPath = __DIR__ . '\\..\\views\\';
        $this->layoutPath = $this->viewPath . 'layouts\\main.php';
    }

    public function render(string $view, array $params = null) : ?string
    {
        if($params) {
            extract($params);
        }
        $content = $this->viewPath . $view . '.php';
        ob_start();
        include $this->layoutPath;
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
}