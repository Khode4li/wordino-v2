<?php
namespace system\model;

use system\model\trait\injectDatabase;
use system\registry\registry;

abstract class base
{
    use injectDatabase;
    protected $conn;
    protected function __construct()
    {
        $this->conn = self::getDatabaseConn();
    }
}