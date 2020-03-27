<?php


namespace App\src\http;


class Response
{
    private array $headers;

    private int $statusCode;

    private string $content;

    public function __construct(int $statusCode = 200)
    {
        $this->headers = [];
        $this->statusCode = $statusCode;
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

    public function send()
    {
        header('HTTP/1.1 ' . $this->statusCode);
        foreach ( $this->headers as $header ) {
            header($header);
        }
        echo $this->content;
        exit();
    }

    public function redirect(string $url)
    {
        header ('Location: ' . $url);
        exit();
    }
}