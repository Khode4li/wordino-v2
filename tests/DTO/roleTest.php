<?php

namespace tests\DTO;

use PHPUnit\Framework\TestCase;
use system\DTO\role;

class roleTest extends TestCase
{
    public function testPerms()
    {
        $role = new role('test', [['permName' => 'random','has_access' => 1]]);
        $this->assertSame(true, $role->hasAccess('random'));
        $this->assertSame(false, $role->hasAccess('createWordlist'));
    }

    public function testOwner()
    {
        $role = new role('owner', [['permName' => 'random','has_access' => 0]]);
        $this->assertSame(true, $role->hasAccess('random'));
    }

    public function testException()
    {
        $this->expectException(\Exception::class);
        $role = new role('owner', [['permName' => 'random','has_access' => 0]]);
        $role->hasAccess('meowmeownigger');
    }
}