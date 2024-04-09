<?php

namespace system\service\authenticate\trait;

trait redisInject
{
    private static $srdb = null;
    public static function injectRedis($rdb): void
    {
        self::$srdb = $rdb;
    }

    private static function getRedis()
    {
        if (self::$srdb === null)
            throw new \Exception('no redis client is provided!');
        return self::$srdb;
    }
}