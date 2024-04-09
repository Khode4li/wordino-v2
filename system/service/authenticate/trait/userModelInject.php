<?php

namespace system\service\authenticate\trait;

trait userModelInject
{
    private static $suserModel = null;
    public static function injectUserModel($userModel): void
    {
        self::$suserModel = $userModel;
    }

    private static function getUserModel()
    {
        if (self::$suserModel === null)
            throw new \Exception('no userModel object is provided!');
        return self::$suserModel;
    }
}