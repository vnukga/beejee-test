<?php


namespace App\src\database;


use Exception;
use PDO;
use PDOStatement;
use stdClass;

class Connection
{
    private PDO $dbh;

    private ?string $query;

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

    public function getDbh()
    {
        return $this->dbh;
    }

    public function query(string $query)
    {
        return $this->dbh->query($query);
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function commit()
    {
        return $this->dbh->commit();
    }

    public function rollback()
    {
        return $this->dbh->rollBack();
    }

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

    public function count(string $tableName)
    {
        $this->query = 'SELECT COUNT(*) ';
        $this->from($tableName);
        $count = $this->dbh->query($this->query)->fetch()[0];
        $this->query = null;
        return $count;
    }

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

    public function from(string $tableName)
    {
        $this->query .= ' FROM ' . $tableName;
        return $this;
    }

    public function where(array $conditions)
    {
        $conditionString = '';
        foreach ($conditions as $key => $condition){
            $conditionString .= ' ' . $key . ' = ' . $this->quote($condition);
        }
        $this->query .= ' WHERE ' . $conditionString;
        return $this;
    }

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

    public function limit(int $rowCount = null)
    {
        if($rowCount) {
            $this->query .= ' LIMIT ' . $rowCount;
        }
        return $this;
    }

    public function offset(int $offset = null)
    {
        if($offset) {
            $this->query .= ' OFFSET ' . $offset;
        }
        return $this;
    }

    public function insert(string $table, array $values)
    {
        $this->prepareInsertQuery($table, $values);
        foreach ($values as $key => $value){
            $this->preparedQuery->bindValue(':' . $key, $value, $this->getBindType($value));
        }
        $this->execute();
    }

    public function update(string $table, array $values, int $id)
    {
        $this->prepareUpdateQuery($table, $values, $id);
        foreach ($values as $key => $value){
            $this->preparedQuery->bindValue(':' . $key, $value, $this->getBindType($value));
        }
        $this->execute();
    }


    public function prepare()
    {
        $this->preparedQuery = $this->dbh->prepare($this->query);
        return $this;
    }

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

    private function quote(string $query)
    {
        return $this->dbh->quote($query);
    }

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