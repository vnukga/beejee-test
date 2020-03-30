<?php


namespace App\src;

use App\src\database\Connection;

/**
 * Abstract model's class
 * @package App\src
 */
abstract class ModelAbstract
{
    /**
     * Model's entity's id
     *
     * @var int
     */
    public int $id;

    /**
     * Connection instance
     *
     * @var Connection
     */
    public Connection $connection;

    /**
     * Model's errors
     *
     * @var array
     */
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

    /**
     * Returns model's fields
     *
     * @return array
     */
    public abstract function fields() : array;

    /**
     * Returns model's table name
     *
     * @return string
     */
    public abstract function getTableName() : string;

    /**
     * Returns model's class name
     *
     * @return string
     */
    public abstract function getClassName() : string;

    /**
     * Inserts new row to model's table
     */
    public function insert() : void
    {
        $tableName = $this->getTableName();
        $this->connection->insert($tableName, $this->fields());
    }

    /**
     * Saves model to database
     */
    public function save() : void
    {
        $tableName = $this->getTableName();
        $this->connection->update($tableName, $this->fields(), $this->id);
    }

    public function countAll() : int
    {
        $tableName = $this->getTableName();
        return $this->connection->count($tableName);
    }

    /**
     * Finds one model's entity in database
     *
     * @param array $fields
     * @return mixed
     */
    public function findOne(array $fields) : ?ModelAbstract
    {
        return $this->connection->select()
            ->from($this->getTableName())
            ->where($fields)
            ->execute($this->getClassName())[0];
    }

    /**
     * Finds list of model's entities in database
     *
     * @param int|null $limit
     * @param int|null $offset
     * @param array $order
     * @return ModelAbstract[]|bool|null
     */
    public function findAll(int $limit = null, int $offset = null, array $order = ['ID ASC'])
    {
        return $this->connection->select()
            ->from($this->getTableName())
            ->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->execute($this->getClassName());
    }

    /**
     * Loads data to model
     *
     * @param array $parameters
     * @return bool
     */
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

    /**
     * Returns model's errors
     *
     * @return array|null
     */
    public function getErrors() : ?array
    {
        if(isset($this->errors)) {
            return $this->errors;
        }
        return null;
    }
}