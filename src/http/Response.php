<?php


namespace App\src\http;


class Response
{
    const STATUS_OK = 200;

    const STATUS_FORBIDDEN = 403;

    const STATUS_NOT_FOUND = 404;

    private array $headers;

    private int $statusCode;

    private string $content;

    private Cookie $cookie;

    private Session $session;

    public function __construct(int $statusCode = self::STATUS_OK)
    {
        $this->headers = [];
        $this->statusCode = $statusCode;
        $this->cookie = new Cookie();
        $this->session = new Session();
    }

    public function addHeader(string $header)
    {
        $this->headers[] = $header;
        return $this;
    }

    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    public function setFlash(string $category, string $message)
    {
        $this->session->set('flash-type', $category);
        $this->session->set('flash-message', $message);
    }

    public function getFlash() : ?array
    {
        $flashType = $this->session->get('flash-type');
        if($flashType){
            $message = $this->session->get('flash-message');
            return [
                'type' => $flashType,
                'text' => $message
            ];
        }
        return null;
    }

    public function clearFlash() : void
    {
        $this->session->unset('flash-type');
        $this->session->unset('flash-message');
    }

    public function send(bool $isJson = false)
    {
        header('HTTP/1.1 ' . $this->statusCode);
        if($isJson){
            $this->addHeader('Content-Type: application/json');
        }
        foreach ( $this->headers as $header ) {
            header($header);
        }
        echo $this->content;
        exit();
    }

    public function redirect(string $url)
    {
        header('Location: ' . $url);
        exit();
    }
}