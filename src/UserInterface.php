<?php


namespace App\src;


interface UserInterface
{
    public function login(string $login, string $password) : void;

    public function isGuest() : bool;
//
    public function logout() : void;

    public function setIsGuest(bool $isGuest) : void;

    public function getUsername()  : ?string;
}