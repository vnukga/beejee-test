<?php


namespace App\src;

/**
 * Interface for user's entity
 * @package App\src
 */
interface UserInterface
{
    /**
     * Logs user in
     *
     * @param string $login
     * @param string $password
     */
    public function login(string $login, string $password) : void;

    /**
     * Returns if user is guest
     *
     * @return bool
     */
    public function isGuest() : bool;

    /**
     * Logs user out
     */
    public function logout() : void;

    /**
     * Sets if user is guest
     *
     * @param bool $isGuest
     */
    public function setIsGuest(bool $isGuest) : void;

    /**
     * Returns user's name
     *
     * @return string|null
     */
    public function getUsername()  : ?string;
}