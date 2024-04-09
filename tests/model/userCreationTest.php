<?php

namespace tests\model;

use PHPUnit\Framework\TestCase;
use system\model\role;
use system\model\user;

class userCreationTest extends TestCase
{
    public function testUserObjectCreation()
    {
        $DB = new class
        {
            private $counter = 0;
            public function select()
            {
                if ($this->counter === 0){
                    return [['username' => 'charlix', 'role_id' => 2]];
                    $this->counter += 1;
                }
                return ['reader'];
            }

            public function query($x, $y)
            {
                return $this;
            }

            public function fetchAll()
            {
                return [['permName' => 'random','has_access' => 1]];
            }
        };

        role::injectDatabase($DB);
        user::injectDatabase($DB);

        $uModel = user::getInstance();
        $u = $uModel->createUserObject(2);

        $this->assertSame('charlix', $u->getUsername());
        $this->assertSame('reader', $u->getRoleName());
        $this->assertSame(true, $u->getRole()->hasAccess('random'));
    }
}