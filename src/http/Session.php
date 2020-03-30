<?php


namespace App\src\http;


class Session
{
    private array $values;

    public function __construct()
    {
        session_start();
        $this->values = $_SESSION;
    }

    public function startUserSession(string $username) : void
    {
        $_SESSION['user'] = $username;
    }

    public function finishUserSession() : void
    {
        session_unset();
        session_destroy();
        $this->values = $_SESSION;
    }

    public function getUserSession() : ?string
    {
        return $this->values['user'];
    }

    public function set(string $name, string $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get(string $name)
    {
        return $_SESSION[$name];
    }

    public function unset(string $name)
    {
        unset($_SESSION[$name]);
    }
}