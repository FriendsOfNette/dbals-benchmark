<?php

set_time_limit(0);
ini_set('memory_limit', '512M');
date_default_timezone_set('Europe/Prague');

use Nette\Database\Connection;
use Nette\Database\Helpers;

if (@!include __DIR__ . '/data.sql') {
    echo 'Extract employees.sql.7z to import/data.sql';
    exit(1);
}

if (@!include $dir . '/vendor/autoload.php') {
    echo 'Install library using `installall` for all or manually `composer install` for every library';
    exit(1);
}

$connection = new Connection('mysql:dbname=benchmark', 'root', NULL);

if (($count = Helpers::loadFromFile($connection, __DIR__ . '/data.sql')) > 0) {
    echo "OK ($count commands executed) \n";
} else {
    echo "FAILED \n";
}

