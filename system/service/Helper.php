<?php

namespace system\service;

class Helper
{
    public static function randomCharacter(int $length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
        return $randomString;
    }

    public static function response(array|string $data,int $status_code, string $status = 'ok', bool $die = false)
    {
        $data = ['status' => $status, 'data' => $data];
        http_response_code($status_code);
        header('Content-Type: application/json');
        echo json_encode($data);
        if ($die)
            die();
    }

    public static function requiredParams(array $params)
    {
        foreach ($params as $pName){
            if (!isset($_POST[$pName]) || empty($_POST[$pName]))
                Helper::response('not all required parameter is sended!',200,'not ok',true);
        }
    }
}