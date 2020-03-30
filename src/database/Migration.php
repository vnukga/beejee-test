<?php


namespace App\src\database;

/**
 * Class Migration
 *
 * @package App\src\database
 */
class Migration
{
    /**
     * Migration's file name
     *
     * @var string
     */
    private string $fileName;

    /**
     * SQL query from migration's file
     *
     * @var false|string
     */
    private string $query;

    /**
     * Migration's id
     *
     * @var int
     */
    private int $id;

    /**
     * Connection's instance
     *
     * @var Connection
     */
    private Connection $connection;

    /**
     * Message for CLI-mode
     *
     * @var string
     */
    private string $message;

    public function __construct(string $fileName, Connection $connection)
    {
        $this->fileName = basename($fileName);
        $this->query = file_get_contents($fileName);
        $this->id = $this->getIdFromFileName();
        $this->connection = $connection;
    }

    /**
     * Returns migration's id
     *
     * @return int
     */
    private function getIdFromFileName() : int
    {
        $nameParts = explode('_', $this->fileName);
        $id = +$nameParts[0];
        return $id;
    }

    /**
     * Applies a migration
     */
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

    /**
     * Echos message to CLI
     */
    public function getMessage() : void
    {
        echo $this->message . PHP_EOL;
    }

    /**
     * Returns migration's actuality
     *
     * @return bool
     */
    private function checkActuality() : bool
    {
        $lastMigration = $this->connection->select(['name'])->from('migrations')->orderBy(['name DESC'])->execute();
        if(!$lastMigration) {
            return true;
        }
        $lastMigrationId = explode('_', $lastMigration->name)[0];
        return $lastMigrationId < $this->id;
    }

    /**
     * Logs migration name and applying time to database
     */
    private function logToMigrationsTable() : void
    {
        $this->connection->insert('migrations', [
            'name' => $this->fileName,
            'time' => date('Y-m-d H:i:s',time())
        ]);
    }
}