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
 * @version  1.0-dev
 * @link     https://www.flamecore.org
 * @license  https://opensource.org/licenses/MIT MIT License
 */

namespace FlameCore\Container;

use FlameCore\Container\Exceptions\LockedException;

/**
 * Describes the interface of a container that exposes methods to lock and unlock entries.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface LockableContainerInterface extends ModifiableContainerInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws LockedException Attempt to override a locked entry, which is not allowed.
     */
    public function set($id, $value);

    /**
     * {@inheritdoc}
     *
     * @throws LockedException Attempt to remove a locked entry, which is not allowed.
     */
    public function remove($id);

    /**
     * Locks the entry with given identifier.
     *
     * @param string $id The identifier of the entry
     */
    public function lock($id);

    /**
     * Unlocks the entry with given identifier.
     *
     * @param string $id The identifier of the entry
     */
    public function unlock($id);

    /**
     * Checks if the entry with given identifier is locked.
     *
     * @param string $id The identifier of the entry
     *
     * @return bool
     */
    public function isLocked($id);
}
