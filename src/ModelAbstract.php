<?php


namespace App\src;


use App\src\database\Connection;

abstract class ModelAbstract
{
    public int $id;

    public Connection $connection;

    protected array $errors;

    public function __construct(Connection $connection = null)
    {
        if(!$connection){
            $connection = Application::app()->getConnection();
        }
        $this->connection = $connection;
        foreach ($this as $key => $value) {
            if(is_string($value)) {
                $this->$key = strip_tags($value);
            }
        }
    }

    public abstract function fields() : array;

    public abstract function getTableName() : string;

    public abstract function getClassName() : string;

    public function insert()
    {
        $tableName = $this->getTableName();
        $this->connection->insert($tableName, $this->fields());
    }

    public function save()
    {
        $tableName = $this->getTableName();
        $this->connection->update($tableName, $this->fields(), $this->id);
    }

    public function countAll()
    {
        $tableName = $this->getTableName();
        return $this->connection->count($tableName);
    }

    public function findOne(array $fields)
    {
        return $this->connection->select()
            ->from($this->getTableName())
            ->where($fields)
            ->execute($this->getClassName())[0];
    }

    public function findAll(int $limit = null, int $offset = null, array $order = ['ID ASC'])
    {
        return $this->connection->select()
            ->from($this->getTableName())
            ->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->execute($this->getClassName());
    }

    public function load(array $parameters) : bool
    {
        foreach ($parameters as $key => $parameter) {
            if(!property_exists($this->getClassName(), $key)) {
                return false;
            }
            $this->$key = $parameter;
        }
        return true;
    }

    public function getErrors() : ?array
    {
        if(isset($this->errors)) {
            return $this->errors;
        }
        return null;
    }
}