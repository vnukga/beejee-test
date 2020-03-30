<?php

namespace App\src\http;

class Request
{
    private ?array $post;

    private ?array $get;

    private array $server;

    private Cookie $cookie;

    private Session $session;

    private ?array $files;

    private ?string $domain;

    private string $urlPrefix;

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->server = $_SERVER;
        $this->cookie = new Cookie();
        $this->session = new Session();
        $this->files = $_FILES;
        $this->domain = $_SERVER['SERVER_NAME'];
        $this->urlPrefix = $_SERVER['HTTPS'] ? 'https://' : 'http://';
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

    public function getRoute() : ?string
    {
        $requestUri = $this->getRequestUri();
        if(strlen($requestUri) === 0){
            return null;
        }
        $argsPosition = stripos($requestUri, '?');
        if($argsPosition > 0) {
            $route = substr($requestUri, 0, $argsPosition);
            return $route;
        }
        return $requestUri;
    }

    public function getHomeUrl()
    {
        return $this->urlPrefix . $this->domain;
    }

    public function getPreviousUrl()
    {
        return $this->server['HTTP_REFERER'];
    }

    public function isPost() : bool
    {
        return $this->server['REQUEST_METHOD'] === 'POST' ? true : false;
    }

    public function isAjax() : bool
    {
        return $this->server['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function cookie() : Cookie
    {
        return $this->cookie;
    }

    public function session() : Session
    {
        return $this->session;
    }
}