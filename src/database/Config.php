<?php


namespace App\src\database;


class Config
{
    private string $driver;

    private string $host;

    private int $port;

    private string $user;

    private string $password;

    private string $dbName;

    private string $dsn;

    public function __construct(array $config)
    {
        $this->driver = $config['driver'];
        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->dbName = $config['db_name'];
        $this->dsn = $this->driver . ':dbname=' . $this->dbName . ';host=' . $this->host;
    }

    public function getDsn()
    {
        return $this->dsn;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }
}