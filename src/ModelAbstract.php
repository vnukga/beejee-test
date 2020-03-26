<?php


namespace App\src;


use App\src\database\Connection;

abstract class ModelAbstract
{
    public Connection $connection;

    public function __construct()
    {
        $this->connection = Application::app()->getConnection();
    }

    public abstract function fields() : array;

    public abstract function getTableName() : string;

    public function insert()
    {
        $tableName = $this->getTableName();
        $this->connection->insert($tableName, $this->fields());
    }
}