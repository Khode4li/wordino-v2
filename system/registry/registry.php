<?php

namespace system\registry;

class registry
{
    private static bool $isTestEnv = false;
    private static array $data = [];

    public static function activateTestEnv()
    {
        self::$isTestEnv = true;
    }
    public static function set(string $name, mixed $service, mixed $testService = null): void
    {
        self::$data[$name] = [
            'prod' => $service,
            'test' => $testService
        ];
    }

    public static function get(string $name): mixed
    {
        self::itemExist($name);
        if (self::$isTestEnv)
            return self::$data[$name]['test'];
        return self::$data[$name]['prod'];
    }

    private static function itemExist(string $name): void
    {
        if (!isset(self::$data[$name]))
            throw new \Exception("the requested item from registry '$name' doesn't exist!");
    }
    public static function setTest(string $name, mixed $service): void
    {
        self::$data[$name]['test'] = $service;
    }
}