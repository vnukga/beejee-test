<?php

namespace App\src\http;

class Request
{
    private ?array $post;

    private ?array $get;

    private array $server;

    private ?array $cookies;

    private ?array $session;

    private ?array $files;

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->server = $_SERVER;
        $this->cookies = $_COOKIE;
        $this->session = $_SESSION;
        $this->files = $_FILES;
    }

    public function get(string $parameter = null)
    {
        if($parameter){
            return $this->get[$parameter];
        }
        return $this->get;
    }

    public function post(string $parameter = null)
    {
        if($parameter){
            return $this->post[$parameter];
        }
        return $this->post;
    }

    public function getParameterFromRequest(string $parameter)
    {
        return $this->get[$parameter] ?? $this->post[$parameter];
    }

    public function getRequestUri()
    {
        $requestUri =  $this->server['REQUEST_URI'];
        return substr($requestUri, 1);
    }

    public function getRoute()
    {
        $requestUri = $this->getRequestUri();
        $argsPosition = stripos($requestUri, '?');
        if($argsPosition > 0) {
            $route = substr($requestUri, 0, $argsPosition);
            return $route;
        }
        return $requestUri;
    }
}