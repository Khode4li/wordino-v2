<?php
namespace system\model\trait;

trait singleton
{
    private static $instance = null;

    private function __construct()
    {
        return parent::__construct();
    }

    public static function getInstance()
    {
        if (self::$instance === null){
            $c = get_called_class();
            self::$instance = new $c();
        }
        return self::$instance;
    }
}