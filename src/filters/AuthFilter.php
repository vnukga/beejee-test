<?php


namespace App\src\filters;

use App\src\Application;

/**
 * Class AuthFilter
 *
 * Filter for access control based on user's authentication
 *
 * @package App\src\filters
 */
class AuthFilter implements FilterInterface
{
    /**
     * Filter's configuration
     *
     * @var array
     */
    private array $config;

    /**
     * User's role
     *
     * @var string
     */
    private string $role;

    /**
     * Permissions list
     *
     * @var array
     */
    private array $permissions;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Runs filter
     *
     * @param string $route
     * @return bool
     */
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