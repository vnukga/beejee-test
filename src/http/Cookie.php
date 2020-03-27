<?php


namespace App\src\http;


class Cookie
{
    private array $cookies;

    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }

    public function set(string $name, string $value,  int $expire = 86400,
                        string $path = '/', string $domain = null,
                        bool $secure = false, bool $httponly = false) : bool
    {
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function get(string $name)
    {
        return $this->cookies[$name];
    }
}