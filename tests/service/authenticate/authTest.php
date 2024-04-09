<?php

namespace tests\service\authenticate;

use PHPUnit\Framework\TestCase;
use system\service\authenticate\auth;

class authTest extends TestCase
{
    public function testLogin()
    {
        $u = new class
        {
            public function checkUserPass($u, $p)
            {
                return ['id' => '5'];
            }

            public function createUserObject($u)
            {
                if ($u === '5')
                    return ['asd'];
            }
        };
        auth::injectUserModel($u);
        $rdb = new class
        {
            private $data = [];
            public function executeRaw($arr)
            {
                if ($arr[0] === 'set'){
                    $this->data[$arr[1]] = $arr[2];
                }
            }

            public function get($id)
            {
                if (!isset($this->data[$id]))
                    return null;
                return $this->data[$id];
            }
        };

        auth::injectRedis($rdb);

        $auth = auth::getInstance();
        $token = $auth->login('charlie', 'abcd');

        $this->assertSame(false, $auth->checkLogin('asd'));
        $this->assertSame(['asd'], $auth->checkLogin($token));
    }
}