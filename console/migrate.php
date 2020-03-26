<?php

use App\src\Application;

$root = __DIR__ . '/../';

require $root . 'vendor/autoload.php';

$configDir = $root . 'config';
$config = [];
foreach(scandir($configDir) as $configFile){
    if($configFile === '.' || $configFile === '..'){
        continue;
    }
    $config = array_merge($config, require $configDir . DIRECTORY_SEPARATOR . $configFile);
}


$application = new Application($config);
$connection = $application->getConnection();
$migrations = $application->getMigrations();

$createMigrationTableQuery = <<<SQL
    CREATE TABLE IF NOT EXISTS migrations (
        name varchar(255) not null,
        time timestamp default NOW()
    )
SQL;

$connection->query($createMigrationTableQuery);

foreach($migrations as $migration) {
    $migration->apply();
    $migration->getMessage();
}

