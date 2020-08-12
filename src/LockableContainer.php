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

declare(strict_types=1);

namespace FlameCore\Container;

use FlameCore\Container\Exceptions\LockedException;

/**
 * The LockableContainer class
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class LockableContainer extends Container implements LockableContainerInterface
{
    /**
     * List of locked entries
     *
     * @var array
     */
    protected $locked = [];

    /**
     * {@inheritdoc}
     */
    public function lock($id)
    {
        $this->validateName($id);

        if (!$this->isLocked($id)) {
            $this->locked[] = $id;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unlock($id)
    {
        $this->validateName($id);

        $index = array_search($id, $this->locked);
        if ($index !== false) {
            unset($this->locked[$index]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isLocked($id)
    {
        return in_array($id, $this->locked);
    }

    /**
     * {@inheritdoc}
     *
     * @throws LockedException Attempt to override a locked entry, which is not allowed.
     */
    protected function validateSet($id, $value): void
    {
        parent::validateSet($id, $value);

        if ($this->isLocked($id)) {
            throw new LockedException(sprintf('Cannot override locked entry "%s" in %s container.', $id, $this->name));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws LockedException Attempt to remove a locked entry, which is not allowed.
     */
    protected function validateRemove($id): void
    {
        parent::validateRemove($id);

        if ($this->isLocked($id)) {
            throw new LockedException(sprintf('Cannot remove locked entry "%s" from %s container.', $id, $this->name));
        }
    }
}
