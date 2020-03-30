<?php

namespace App\src;

use App\models\Administrator;
use App\src\database\Config;
use App\src\database\Connection;
use App\src\database\Migration;
use App\src\http\Router;

class Application
{
    private static $instance;

    private array $config;

    private string $controllersNamespace;

    private Connection $connection;

    private Router $router;

    private UserInterface $user;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->controllersNamespace = $config['controllersNamespace'];
        $dbConfig = $this->config['db'];
        $this->connection = new Connection(new Config($dbConfig));
        $this->router = new Router();
        $this->user = new Administrator($this->connection);
    }

    public static function init(array $config)
    {
        if(self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public static function app() : self
    {
        return self::$instance;
    }

    public function run()
    {
        $this->authorizeUserFromSession();
        $this->router->handle();
    }

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

    public function getConnection()
    {
        return $this->connection;
    }

    public function getRequest()
    {
        return $this->router->getRequest();
    }

    public function getUser()
    {
        return $this->user;
    }

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
     * @param string $parameter
     * @return array|string|null
     */
    public function getConfigParameter(string $parameter)
    {
        return $this->config[$parameter];
    }
}