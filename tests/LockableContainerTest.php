<?php
/**
 * FlameCore Container
 * Copyright (C) 2020 FlameCore Team
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * @package  FlameCore\Container
 * @version  1.0
 * @link     https://www.flamecore.org
 * @license  https://opensource.org/licenses/MIT MIT License
 */

namespace FlameCore\Container\Tests;

use FlameCore\Container\LockableContainer;
use FlameCore\Container\Exceptions\LockedException;

/**
 * Test class for LockableContainer
 */
class LockableContainerTest extends ContainerTest
{
    public function testLockUnlock()
    {
        $container = $this->createContainer();
        $container->lock('foo');

        $this->assertTrue($container->isLocked('foo'));

        $container->unlock('foo');

        $this->assertFalse($container->isLocked('foo'));

        $container->set('foo', new TestClass());
        $container->remove('foo');
    }

    public function testSetThrowsExceptionWhenEntryLocked()
    {
        $this->expectException(LockedException::class);
        $this->expectExceptionMessage("override locked entry");

        $container = $this->createContainer();
        $container->lock('foo');
        $container->set('foo', function () {
            return new TestClass();
        });
    }

    public function testRemoveThrowsExceptionWhenEntryLocked()
    {
        $this->expectException(LockedException::class);
        $this->expectExceptionMessage("remove locked entry");
        
        $container = $this->createContainer();
        $container->set('foo', new TestClass());
        $container->lock('foo');
        $container->remove('foo');
    }

    /**
     * @return LockableContainer
     */
    protected function createContainer()
    {
        return new LockableContainer();
    }
}
