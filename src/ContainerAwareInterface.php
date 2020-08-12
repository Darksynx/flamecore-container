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

namespace FlameCore\Container;

use Psr\Container\ContainerInterface;

/**
 * Interface for container-aware classes
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface ContainerAwareInterface
{
    /**
     * Sets the container.
     *
     * @param ContainerInterface $container The container
     */
    public function setContainer(ContainerInterface $container): void;

    /**
     * Gets the container.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;
}
