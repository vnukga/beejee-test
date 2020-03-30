<?php

namespace App\src\database;

use Exception;
use PDO;
use PDOStatement;
use stdClass;

/**
 * Class Connection
 *
 * Database connection
 * @package App\src\database
 */
class Connection
{
    /**
     * DBH class
     *
     * @var PDO
     */
    private PDO $dbh;

    /**
     * Auery string
     *
     * @var string|null
     */
    private ?string $query;

    /**
     * Query string prepared for value's binding
     *
     * @var PDOStatement|null
     */
    private ?PDOStatement $preparedQuery;

    public function __construct(Config $config)
    {
        $this->dbh = new PDO(
            $config->getDsn(),
            $config->getUser(),
            $config->getPassword()
        );
        $this->preparedQuery = null;
    }

    /**
     * Returns DBH instance
     *
     * @return PDO
     */
    public function getDbh() : PDO
    {
        return $this->dbh;
    }

    /**
     * Executes an SQL statement, returning a result set as a PDOStatement object
     *
     * @param string $query
     * @return false|PDOStatement
     */
    public function query(string $query)
    {
        return $this->dbh->query($query);
    }

    /**
     * Begins transaction
     *
     * @return bool
     */
    public function beginTransaction() : bool
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * Commits transaction
     *
     * @return bool
     */
    public function commit() : bool
    {
        return $this->dbh->commit();
    }

    /**
     * Rolls back transaction
     *
     * @return bool
     */
    public function rollback() : bool
    {
        return $this->dbh->rollBack();
    }

    /**
     * Executes query inside a transaction
     *
     * @param string $query
     * @return bool
     */
    public function transactQuery(string $query) : bool
    {
        try {
            $this->beginTransaction();
            $this->query($query);
            $this->commit();
            return true;
        } catch (Exception $exception) {
            $this->rollback();
            return false;
        }
    }

    /**
     * Returns rows of $tablename table quantity
     *
     * @param string $tableName
     * @return int
     */
    public function count(string $tableName) : int
    {
        $this->query = 'SELECT COUNT(*) ';
        $this->from($tableName);
        $count = $this->dbh->query($this->query)->fetch()[0];
        $this->query = null;
        return $count;
    }

    /**
     * Adds SELECT statement to query
     *
     * @param array|null $fields
     * @return $this
     */
    public function select(array $fields = null)
    {
        if(!$fields){
            $fields = '*';
        } else {
            $fields = implode(',', $fields);
        }
        $this->query = 'SELECT ' . $fields;
        return $this;
    }

    /**
     * Adds FROM statement to query
     *
     * @param string $tableName
     * @return $this
     */
    public function from(string $tableName)
    {
        $this->query .= ' FROM ' . $tableName;
        return $this;
    }

    /**
     * Adds WHERE statement to query
     *
     * @param array $conditions
     * @return $this
     */
    public function where(array $conditions)
    {
        $conditionString = '';
        foreach ($conditions as $key => $condition){
            $conditionString .= ' ' . $key . ' = ' . $this->quote($condition);
        }
        $this->query .= ' WHERE ' . $conditionString;
        return $this;
    }

    /**
     * Adds ORDER BY statement to query
     *
     * @param array $fields
     * @return $this
     */
    public function orderBy(array $fields)
    {
        $conditionString = '';
        foreach ($fields as $key => $field){
            if($key === 0){
                $conditionString .= ' ' . $field;
            } else {
                $conditionString .= ', ' . $field;
            }

        }
        $this->query .= ' ORDER BY' . $conditionString;
        return $this;
    }

    /**
     * Adds LIMIT statement to query
     *
     * @param int|null $rowCount
     * @return $this
     */
    public function limit(int $rowCount = null)
    {
        if($rowCount) {
            $this->query .= ' LIMIT ' . $rowCount;
        }
        return $this;
    }

    /**
     * Adds OFFSET statement to query
     *
     * @param int|null $offset
     * @return $this
     */
    public function offset(int $offset = null)
    {
        if($offset) {
            $this->query .= ' OFFSET ' . $offset;
        }
        return $this;
    }

    /**
     * Insert new row to table
     *
     * @param string $table
     * @param array $values
     */
    public function insert(string $table, array $values) : void
    {
        $this->prepareInsertQuery($table, $values);
        foreach ($values as $key => $value){
            $this->preparedQuery->bindValue(':' . $key, $value, $this->getBindType($value));
        }
        $this->execute();
    }

    /**
     * Updates row in table
     *
     * @param string $table
     * @param array $values
     * @param int $id
     */
    public function update(string $table, array $values, int $id)
    {
        $this->prepareUpdateQuery($table, $values, $id);
        foreach ($values as $key => $value){
            $this->preparedQuery->bindValue(':' . $key, $value, $this->getBindType($value));
        }
        $this->execute();
    }

    /**
     * Prepares query for value's binding
     *
     * @return $this
     */
    public function prepare()
    {
        $this->preparedQuery = $this->dbh->prepare($this->query);
        return $this;
    }

    /**
     * Prepares query with INSERT statement
     *
     * @param string $table
     * @param array $values
     */
    private function prepareInsertQuery(string $table, array $values)
    {
        $this->query = 'INSERT INTO ' . $table . ' (';
        $fields = array_keys($values);
        $this->query .= implode(', ', $fields);
        $this->query .= ') VALUES (:';
        $this->query .= implode(', :', $fields);
        $this->query .= ')';
        $this->prepare();
    }

    /**
     * Prepares query with UPDATE statement
     *
     * @param string $table
     * @param array $values
     * @param int $id
     */
    private function prepareUpdateQuery(string $table, array $values, int $id)
    {
        $this->query = 'UPDATE ' . $table . ' SET ';
        $fields = array_keys($values);
        foreach ($fields as &$field) {
            $field .= ' = :' . $field;
        }
        $this->query .= implode(',', $fields);
        $this->query .= ' WHERE id = ' . $id;
        $this->prepare();
    }

    /**
     * Executes query
     *
     * @param string $className
     * @return array|bool|null
     */
    public function execute($className = stdClass::class)
    {
        if(!$this->preparedQuery){
            $result = $this->dbh->query($this->query);
            $this->query = null;
            if(!$result) {
                return null;
            }
            return $result->fetchAll(PDO::FETCH_CLASS, $className);
        }
        if($this->preparedQuery->execute()){
            $this->preparedQuery = null;
            return true;
        }
        return false;
    }

    /**
     * Quotes value befor inserting
     *
     * @param string $query
     * @return false|string
     */
    private function quote(string $query)
    {
        return $this->dbh->quote($query);
    }

    /**
     * Returns type for value's binding
     *
     * @param $value
     * @return int
     */
    private function getBindType($value){
        switch(gettype($value)){
            case 'string':
                return PDO::PARAM_STR;
                break;
            case 'integer':
                return PDO::PARAM_INT;
                break;
            case 'boolean':
                return PDO::PARAM_BOOL;
                break;
            default:
                return PDO::PARAM_STR;
        }
    }
}