<?php

namespace system\model\trait;

trait injectDatabase
{
    protected static $dbConn = null;
    public static function injectDatabase($dbConn)
    {
        self::$dbConn = $dbConn;
    }

    protected static function getDatabaseConn()
    {
        if (self::$dbConn === null)
            throw new \Exception('database Connection is not provided!');
        return self::$dbConn;
    }
}