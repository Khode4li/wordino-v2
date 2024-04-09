<?php

namespace system\model;

use system\model\base;
use system\model\trait\singleton;
use system\registry\registry;

class user extends base
{
    use singleton;

    public function checkUserPass(string $username, string $password)
    {
        $data = $this->conn->select('user', '*', ['username' => $username, 'password' => md5($password.registry::get('salt'))]);
        if (isset($data[0]))
            return $data[0];
        return false;
    }

    public function register(string $username, string $password, string|int $rid)
    {
        $q = $this->conn->insert('user', ['username' => $username, 'password' => md5($password.registry::get('salt')), 'role_id' => $rid]);
        return $q;
    }
    public function createUserObject(string|int $UserID)
    {
        $userinfo = $this->getUserInfo($UserID);
        $rObject = role::getInstance()->createRoleObject($userinfo['role_id']);
        $userObject = new \system\DTO\user($userinfo['username'], $rObject);
        return $userObject;
    }

    public function getUserInfo(string|int $UserID)
    {
        $data = $this->conn->select('user','*',['id' => $UserID]);
        if (isset($data[0]))
            return $data[0];
        throw new \Exception("User id {$UserID} not found!");
    }

    public function deleteUserAccount(string|int $uid)
    {
        $this->conn->delete('user', ['id' => $uid]);
    }
}
