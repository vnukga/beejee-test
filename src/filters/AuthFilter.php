<?php


namespace App\src\filters;

use App\src\Application;

class AuthFilter implements FilterInterface
{
    private array $config;

    private string $role;

    private array $permissions;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function run(string $route) : bool
    {
        $this->role = Application::app()->getUser()->isGuest() ? 'guest' : 'user';
        $this->permissions = $this->config['permissions'];
        if(!$this->permissions[$this->role]){
            return true;
        }
        return !in_array($route, $this->permissions[$this->role]);
    }
}