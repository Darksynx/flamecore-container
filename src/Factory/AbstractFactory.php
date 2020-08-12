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

declare(strict_types=1);

namespace FlameCore\Container\Factory;

/**
 * Class AbstractFactory
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractFactory implements FactoryInterface
{
    /**
     * @var object|null
     */
    protected $instance = null;

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * {@inheritdoc}
     */
    public function getInstance()
    {
        if ($this->instance === null) {
            $this->instance = $this->create($this->arguments);
        }

        return $this->instance;
    }
}
