<?php

namespace system\service\permissionSyncer;

class syncer
{
    public static function clean($perms)
    {
        return array_diff_assoc($perms,array_diff_key($perms, perms::DEFAULT));
    }
    public static function sync(array $perms)
    {
        $allTogether = array_merge(perms::DEFAULT, $perms); // all perms even those that are not in the system
        $clear = array_diff_assoc($allTogether,array_diff_key($perms, perms::DEFAULT));
//        print_r($clear);
        return $clear;
    }
}