<?php


namespace App\src\database;


use PDO;
use PDOStatement;

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
        } catch (\Exception $exception) {
            $this->rollback();
            return false;
        }
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

    public function insert(string $table, array $values)
    {
        $this->prepareInsertQuery($table, $values);
        foreach ($values as $key => $value){
            $this->preparedQuery->bindValue(':' . $key, $value);
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

    public function execute()
    {
        if(!$this->preparedQuery){
            $result = $this->dbh->query($this->query);
            $this->query = null;
            if(!$result) {
                return null;
            }
            return $result->fetchObject();
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
        }
    }
}