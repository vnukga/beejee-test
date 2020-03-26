<?php


namespace App\src\database;


class Migration
{
    private string $fileName;

    private string $query;

    private int $id;

    private Connection $connection;

    private string $message;

    public function __construct(string $fileName, Connection $connection)
    {
        $this->fileName = basename($fileName);
        $this->query = file_get_contents($fileName);
        $this->id = $this->getIdFromFileName();
        $this->connection = $connection;
    }

    private function getIdFromFileName() : int
    {
        $nameParts = explode('_', $this->fileName);
        $id = +$nameParts[0];
        return $id;
    }

    public function apply() : void
    {
        if(!$this->checkActuality()){
            $this->message = 'Миграция ' . $this->fileName . ' была применена ранее!';
            return;
        }
        if($this->connection->transactQuery($this->query)){
            $this->logToMigrationsTable();
            $this->message = 'Миграция ' . $this->fileName . ' успешно применена!';
        }
    }

    public function getMessage()
    {
        echo $this->message . PHP_EOL;
    }

    private function checkActuality()
    {
        $lastMigration = $this->connection->select(['name'])->from('migrations')->orderBy(['name DESC'])->execute();
        if(!$lastMigration) {
            return true;
        }
        $lastMigrationId = explode('_', $lastMigration->name)[0];
        return $lastMigrationId < $this->id;
    }

    private function logToMigrationsTable()
    {
        $this->connection->insert('migrations', [
            'name' => $this->fileName,
            'time' => date('Y-m-d H:i:s',time())
        ]);
    }

}