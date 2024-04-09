<?php

namespace system\service;

use system\service\authenticate\auth;

class http
{
    public static function Authenticate()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization']))
            return false;

        $user = auth::getInstance()->checkLogin($headers['Authorization']);
        if ($user === false)
            return false;
        return $user;
    }
}