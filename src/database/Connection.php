<?php


namespace App\src\database;


use PDO;

class Connection
{
    private PDO $dbh;

    public function __construct(Config $config)
    {
        $this->dbh = new PDO(
            $config->getDsn(),
            $config->getUser(),
            $config->getPassword()
        );
    }

    public function getDbh()
    {
        return $this->dbh;
    }
}