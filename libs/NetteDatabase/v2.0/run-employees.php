<?php

use Nette\Caching\Storages\FileStorage;
use Nette\Database\Connection;
use Nette\Database\Reflection\DiscoveredReflection;
use Nette\Framework;

require_once __DIR__ . '/../../../bootstrap.php';

Bootstrap::init();
Bootstrap::check(__DIR__);

$connection = new Connection(
    Bootstrap::$config['db']['driver'] . ':dbname=' . Bootstrap::$config['db']['dbname'],
    Bootstrap::$config['db']['user'],
    Bootstrap::$config['db']['password']
);

$cacheStorage = Bootstrap::$config['cache'] ? new FileStorage(__DIR__ . '/temp') : NULL;
$connection->setCacheStorage($cacheStorage);
$connection->setDatabaseReflection(new DiscoveredReflection($cacheStorage));
$dao = $connection;

$startTime = -microtime(TRUE);
ob_start();

foreach ($dao->table('employees')->limit(Bootstrap::$config['limit']) as $employe) {
    echo "$employe->first_name $employe->last_name ($employe->emp_no)\n";
    
    echo "Salaries:\n";
    foreach ($employe->related('salaries') as $salary) {
        echo $salary->salary, "\n";
    }
    
    echo "Departments:\n";
    foreach ($employe->related('dept_emp') as $department) {
        echo $department->dept->dept_name, "\n";
    }
}

ob_end_clean();
$endTime = microtime(TRUE);

Bootstrap::result('NetteDatabase', '~2.0.0', $startTime, $endTime);
