<?php
use system\registry\registry;

registry::set('dbConn',new \Medoo\Medoo([
    'type' => 'mysql',
    'host' => 'mysql',
    'database' => 'wordino',
    'username' => 'root',
    'password' => '--DBPW--',

    // [optional]
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'port' => 3306
]));

registry::set('rdb', new Predis\Client('tcp://redis:6379'));

registry::set('salt', '--SALT--');

registry::set('userLoginDuration', 172800); #in seconds (2days)

ini_set('memory_limit', '512M');