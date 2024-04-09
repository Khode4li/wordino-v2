<?php

namespace app\controllers;

use system\registry\registry;

abstract class base
{
    protected $conn;
    protected $rdb;

    public function __construct()
    {
        $this->conn = registry::get('dbConn');
        $this->rdb = registry::get('rdb');
    }
}