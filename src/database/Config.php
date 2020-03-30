<?php

namespace App\src\database;

/**
 * Class Config
 *
 * Database's configuration entity
 * @package App\src\database
 */
class Config
{
    /**
     * Driver for connection
     *
     * @var mixed|string
     */
    private string $driver;

    /**
     * Database's host
     *
     * @var mixed|string
     */
    private string $host;

    /**
     * Database's port
     *
     * @var int|mixed
     */
    private int $port;

    /**
     * Database's user
     *
     * @var mixed|string
     */
    private string $user;

    /**
     * Password for connection
     *
     * @var mixed|string
     */
    private string $password;

    /**
     * Name of database
     *
     * @var mixed|string
     */
    private string $dbName;

    /**
     * DSN for using PDO
     *
     * @var string
     */
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

    /**
     * Returns dsn string
     *
     * @return string
     */
    public function getDsn() : string
    {
        return $this->dsn;
    }

    /**
     * Returns database's user
     *
     * @return mixed|string
     */
    public function getUser() : string
    {
        return $this->user;
    }

    /**
     * Returns database's password
     *
     * @return mixed|string
     */
    public function getPassword() : string
    {
        return $this->password;
    }
}