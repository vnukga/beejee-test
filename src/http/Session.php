<?php


namespace App\src\http;

/**
 * Class Session
 * @package App\src\http
 */
class Session
{
    /**
     * $_SESSION's values
     *
     * @var array
     */
    private array $values;

    public function __construct()
    {
        session_start();
        $this->values = $_SESSION;
    }

    /**
     * Starts session for user
     *
     * @param string $username
     */
    public function startUserSession(string $username) : void
    {
        $_SESSION['user'] = $username;
    }

    /**
     * Finishes user's session
     */
    public function finishUserSession() : void
    {
        session_unset();
        session_destroy();
        $this->values = $_SESSION;
    }

    /**
     * Returns user's session
     *
     * @return string|null
     */
    public function getUserSession() : ?string
    {
        return $this->values['user'];
    }

    /**
     * Sets new session's parameter
     *
     * @param string $name
     * @param string $value
     */
    public function set(string $name, string $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Returns session's parameter
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $_SESSION[$name];
    }

    /**
     * Unsets session's parameter
     *
     * @param string $name
     */
    public function unset(string $name)
    {
        unset($_SESSION[$name]);
    }
}