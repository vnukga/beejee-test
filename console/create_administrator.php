<?php

use App\models\Administrator;
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


$application = Application::init($config);
$connection = $application->getConnection();

$administrator = new Administrator();
$administrator->create('admin', '123');