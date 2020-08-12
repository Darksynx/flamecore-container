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

namespace FlameCore\Container\Factory;

/**
 * The Factory interface
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface FactoryInterface
{
    /**
     * Create a new object instance.
     *
     * @param array $arguments
     * @return object
     */
    public function create(array $arguments = []);

    /**
     * Gets the object instance via lazy initialization.
     *
     * @return object
     */
    public function getInstance();
}
