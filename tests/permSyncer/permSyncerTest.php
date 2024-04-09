<?php

namespace tests\permSyncer;

use PHPUnit\Framework\TestCase;
use system\service\permissionSyncer\syncer;

class permSyncerTest extends TestCase
{
    public function testSyncer()
    {
        $syncedPerms = syncer::sync(['readWordlists' => true, 'somethingThatDoesntExist' => true]);

        $this->assertSame(true, $syncedPerms['somethingThatDoesntExist']);
        $this->assertSame(true, $syncedPerms['readWordlists']);
        $this->assertSame(false, $syncedPerms['createWordlist']);
    }
}