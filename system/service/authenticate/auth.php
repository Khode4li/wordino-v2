<?php
namespace system\service\authenticate;

use system\registry\registry;
use system\service\authenticate\trait\redisInject;
use system\service\authenticate\trait\userModelInject;
use system\service\Helper;

class auth
{
    use redisInject;
    use userModelInject;
    private $rdb;
    private $userModel;

    private static $instance = null;
    private function __construct(){
        $this->rdb = self::getRedis();
        $this->userModel = self::getUserModel();
    }

    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

    public function login(string $username, string $password)
    {
        $user = $this->userModel->checkUserPass($username,$password);
        if ($user === false)
            return false;
        $token = Helper::randomCharacter(64);
        $user = $this->userModel->createUserObject($user['id']);
        $this->rdb->executeRaw(['set', $token, serialize($user), 'EX', registry::get('userLoginDuration')]);
        return $token;
    }

    public function checkLogin(string $token)
    {
        $user = $this->rdb->get($token);
        if ($user === null)
            return false;
        $user = unserialize($user);
        return $user;
    }

}