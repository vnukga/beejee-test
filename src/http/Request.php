<?php

namespace App\src\http;

/**
 * Class Request
 * @package App\src\http
 */
class Request
{
    /**
     * Parameters form post-request
     *
     * @var array|null
     */
    private ?array $post;

    /**
     * Parameters form get-request
     *
     * @var array|null
     */
    private ?array $get;

    /**
     * Server's parameters
     *
     * @var array
     */
    private array $server;

    /**
     * Cookies
     *
     * @var Cookie
     */
    private Cookie $cookie;

    /**
     * Session's parameters
     *
     * @var Session
     */
    private Session $session;

    /**
     * $_FILES array
     *
     * @var array|null
     */
    private ?array $files;

    /**
     * Site's domain
     *
     * @var mixed|string|null
     */
    private ?string $domain;

    /**
     * 'http://' or 'https://' prefix
     *
     * @var string
     */
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

    /**
     * Returns values from $_GET
     *
     * @param string|null $parameter
     * @return array|mixed|null
     */
    public function get(string $parameter = null)
    {
        if($parameter){
            return $this->get[$parameter];
        }
        return $this->get;
    }

    /**
     * Returns values from $POST
     *
     * @param string|null $parameter
     * @return array|mixed|null
     */
    public function post(string $parameter = null)
    {
        if($parameter){
            return $this->post[$parameter];
        }
        return $this->post;
    }

    /**
     * Returns parameter's value from $_GET or $_POST
     *
     * @param string|null $parameter
     * @return array|mixed|null
     */
    public function getParameterFromRequest(string $parameter)
    {
        return $this->get[$parameter] ?? $this->post[$parameter];
    }

    /**
     * Returns request uri
     *
     * @return false|string
     */
    public function getRequestUri()
    {
        $requestUri =  $this->server['REQUEST_URI'];
        return substr($requestUri, 1);
    }

    /**
     * Returns current application's route
     *
     * @return string|null
     */
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

    /**
     * Returns base domain url with http or https prefix
     *
     * @return string
     */
    public function getHomeUrl()
    {
        return $this->urlPrefix . $this->domain;
    }

    /**
     * Returns referer url
     *
     * @return mixed
     */
    public function getPreviousUrl()
    {
        return $this->server['HTTP_REFERER'];
    }

    /**
     * Returns if request is post
     *
     * @return bool
     */
    public function isPost() : bool
    {
        return $this->server['REQUEST_METHOD'] === 'POST' ? true : false;
    }

    /**
     * Returns if request is AJAX
     *
     * @return bool
     */
    public function isAjax() : bool
    {
        return $this->server['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * Returns Cookie object
     *
     * @return Cookie
     */
    public function cookie() : Cookie
    {
        return $this->cookie;
    }

    /**
     * Returns Session object
     *
     * @return Session
     */
    public function session() : Session
    {
        return $this->session;
    }
}