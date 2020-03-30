<?php

namespace App\src;

use App\src\database\Config;
use App\src\database\Connection;
use App\src\database\Migration;
use App\src\http\Request;
use App\src\http\Router;

/**
 * Application base class
 * @package App\src
 */
class Application
{
    /**
     * Application instance
     *
     * @var Application
     */
    private static $instance;

    /**
     * Application's configuration
     *
     * @var array
     */
    private array $config;

    /**
     * Application's controllers namespace
     *
     * @var mixed|string
     */
    private string $controllersNamespace;

    /**
     * Connection instance
     *
     * @var Connection
     */
    private Connection $connection;

    /**
     * Router instance
     *
     * @var Router
     */
    private Router $router;

    /**
     * User instance
     *
     * @var UserInterface
     */
    private UserInterface $user;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->controllersNamespace = $config['controllersNamespace'];
        $dbConfig = $this->config['db'];
        $this->connection = new Connection(new Config($dbConfig));
        $this->router = new Router();
        $this->user = new $this->config['userClass']($this->connection);
    }

    /**
     * Initializes an application
     *
     * @param array $config
     * @return Application
     */
    public static function init(array $config)
    {
        if(self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Returns application instance
     *
     * @return static
     */
    public static function app() : self
    {
        return self::$instance;
    }

    /**
     * Main application method
     */
    public function run() : void
    {
        $this->authorizeUserFromSession();
        $this->router->handle();
    }

    /**
     * User's authorization
     */
    private function authorizeUserFromSession() : void
    {
        if($login = $this->getRequest()->session()->getUserSession()){
            $user = $this->user->findOne(['login' => $login]);
            if($user){
                $this->user = $user;
                $this->user->setIsGuest(true);
            }
        }
    }

    /**
     * Returns Connection instance
     *
     * @return Connection
     */
    public function getConnection() : Connection
    {
        return $this->connection;
    }

    /**
     * Returns Request instance
     *
     * @return Request
     */
    public function getRequest() : Request
    {
        return $this->router->getRequest();
    }

    /**
     * Returns user's instance
     *
     * @return UserInterface
     */
    public function getUser() : UserInterface
    {
        return $this->user;
    }

    /**
     * Returns application's migrations
     *
     * @return array
     */
    public function getMigrations() : array
    {
        $migrationsPath = $this->config['migrationsPath'];
        $migrationList = [];
        foreach (scandir($migrationsPath) as $migration){
            if($migration === '.' || $migration === '..') {
                continue;
            }
            $fileName = $migrationsPath . DIRECTORY_SEPARATOR . $migration;
            $migrationList[] = new Migration($fileName, $this->connection);
        }
        return $migrationList;
    }

    /**
     * Returns parameter from config
     *
     * @param string $parameter
     * @return array|string|null
     */
    public function getConfigParameter(string $parameter)
    {
        return $this->config[$parameter];
    }
}