<?php

namespace tests\DTO;

use PHPUnit\Framework\TestCase;
use system\DTO\role;
use system\DTO\user;

class userTest extends TestCase
{
    public function testUser()
    {
        $role = new role('test', [['permName' => 'random','has_access' => 1]]);
        $user = new user('meower', $role);

        $this->assertSame('meower', $user->getUsername());
        $this->assertSame('test', $user->getRoleName());
        $this->assertSame(true, $user->getRole()->hasAccess('random'));
    }
}