<?php


namespace App\src\http;

/**
 * Class Cookie
 *
 * @package App\src\http
 */
class Cookie
{
    /**
     * Cookies from $_COOKIE
     *
     * @var array
     */
    private array $cookies;

    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }

    /**
     * Sets new cookie
     *
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string|null $domain
     * @param bool $secure
     * @param bool $httponly
     * @return bool
     */
    public function set(string $name, string $value,  int $expire = 86400,
                        string $path = '/', string $domain = null,
                        bool $secure = false, bool $httponly = false) : bool
    {
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * Returns cookie, if exists
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->cookies[$name];
    }
}