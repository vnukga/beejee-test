<?php


namespace App\src;


use App\src\database\Connection;

abstract class ModelAbstract
{
    public Connection $connection;

    public function __construct(Connection $connection = null)
    {
        if(!$connection){
            $connection = Application::app()->getConnection();
        }
        $this->connection = $connection;
    }

    public abstract function fields() : array;

    public abstract function getTableName() : string;

    public abstract function class() : string;

    public function insert()
    {
        $tableName = $this->getTableName();
        $this->connection->insert($tableName, $this->fields());
    }

    public function findOne(array $fields)
    {
        return $this->connection->select()->from($this->getTableName())->where($fields)->execute($this->class());
    }
}