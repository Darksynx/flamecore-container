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
use Psr\Container\NotFoundExceptionInterface;

/**
 * Describes the interface of a container that exposes methods to write and remove its entries.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface ModifiableContainerInterface extends ContainerInterface
{
    /**
     * Assigns a value to the entry with given name.
     *
     * @param string $id The name of the entry
     * @param object|\Closure $value The value to assign. Can be an object or an object factory (any callable).
     */
    public function set($id, $value);

    /**
     * Removes the value from entry with given name.
     *
     * @param string $id The name of the entry
     *
     * @throws NotFoundExceptionInterface No entry was found for this identifier.
     */
    public function remove($id);
}
