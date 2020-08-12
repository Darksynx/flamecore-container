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

use FlameCore\Container\Container;
use FlameCore\Container\ContainerAwareTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test class for ContainerAware*
 */
class ContainerAwareTest extends TestCase
{
    public function test()
    {
        $object = new class {
            use ContainerAwareTrait;
        };

        $container = new Container();
        $object->setContainer($container);
        $this->assertSame($container, $object->getContainer());
    }
}
