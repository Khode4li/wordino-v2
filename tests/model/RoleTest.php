<?php

namespace tests\model;

use PHPUnit\Framework\TestCase;
use system\model\role;

class RoleTest extends TestCase
{
    public function testRoleModel()
    {
        $db_obj = new class
        {
            public function select()
            {
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

        role::injectDatabase($db_obj);
        $rModel = role::getInstance();
        $r = $rModel->createRoleObject(2);
        $this->assertSame(true, $r->hasAccess('random'));
        $this->assertSame(false, $r->hasAccess('createWordlist'));
    }
}