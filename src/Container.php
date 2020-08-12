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

use FlameCore\Container\Definition\Definition;
use FlameCore\Container\Factory\Factory;
use FlameCore\Container\Factory\FactoryInterface;
use FlameCore\Container\Exceptions\NotFoundException;
use FlameCore\Container\Exceptions\WrongTypeException;
use FlameCore\Container\Exceptions\AlreadyInUseException;
use FlameCore\Container\Exceptions\InvalidDefinitionException;

class Container implements ModifiableContainerInterface, DefinableContainerInterface, FactoryContainerInterface
{
    /**
     * The name of the container
     *
     * @var string
     */
    protected $name;

    /**
     * The type map
     *
     * @var array
     */
    protected $typeMap = [];

    /**
     * The registered entries
     *
     * @var mixed[]
     */
    private $entries = [];

    /**
     * Creates the container.
     */
    public function __construct()
    {
        $this->name = get_class($this);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        $this->validateName($id);

        return isset($this->entries[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $value = $this->getRaw($id);

        if ($value instanceof Factory) {
            return $value->getInstance();
        }

        return $value;
    }

    /**
     * Finds an entry of the container by its identifier and returns its raw value if it is a factory.
     *
     * @param string $id The identifier of the entry
     * @return object
     *
     * @throws NotFoundException No entry was found for this identifier.
     */
    public function getRaw($id)
    {
        $this->validateName($id);

        if (!isset($this->entries[$id])) {
            throw new NotFoundException(sprintf('The entry with identifier "%s" was not found.', $id));
        }

        return $this->entries[$id];
    }

    /**
     * Returns the value of the entry with given identifier if it is set, or the fallback value if the entry is not set.
     *
     * @param string $id The identifier of the entry
     * @param object|\Closure $default The fallback value
     * @param bool $raw Force returning the raw value if the entry is a factory
     * @return object
     */
    public function getOr($id, $default, bool $raw = false)
    {
        try {
            return $raw ? $this->getRaw($id) : $this->get($id);
        } catch (NotFoundException $e) {
            if ($default instanceof \Closure) {
                $default = new Factory($default);
            }

            if ($default instanceof FactoryInterface && !$raw) {
                return $default->getInstance();
            }

            return $default;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function make($id, array $arguments = [])
    {
        $factory = $this->getRaw($id);

        if (!$factory instanceof FactoryInterface) {
            throw new WrongTypeException(sprintf('The entry "%s" is not a factory.', $id));
        }

        return $factory->create($arguments);
    }

    /**
     * {@inheritdoc}
     *
     * @throws WrongTypeException Value with wrong type given.
     * @throws \InvalidArgumentException Invalid identifier given.
     */
    public function set($id, $value)
    {
        $value = $value instanceof \Closure ? new Factory($value) : $value;

        $this->validateSet($id, $value);

        $this->entries[$id] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($id)
    {
        $this->validateRemove($id);

        unset($this->entries[$id]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException Invalid identifier given.
     */
    public function define($id, $type)
    {
        $this->validateName($id);

        if ($this->has($id)) {
            throw new AlreadyInUseException(sprintf('Cannot define entry "%s" because a value is already assigned to it.', $id));
        }

        $type = (string) $type;

        if (!class_exists($type)) {
            throw new InvalidDefinitionException(sprintf('Cannot use non-existing class %s as type to define entry "%s"', $type, $id));
        }

        $this->typeMap[$id] = $type;
    }

    /**
     * Defines multiple entries in the type map.
     *
     * @param array $typeMap The type mapping
     *
     * @throws \InvalidArgumentException An identifier is invalid.
     * @throws AlreadyInUseException An entry to define is already in use.
     */
    public function defineMultiple(array $typeMap)
    {
        foreach ($typeMap as $id => $type) {
            $this->define($id, $type);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isDefined($id)
    {
        $this->validateName($id);

        return isset($this->typeMap[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition($id)
    {
        $this->validateName($id);

        return isset($this->typeMap[$id]) ? new Definition($this->typeMap[$id]) : null;
    }

    /**
     * Checks if the entry is a factory.
     *
     * @param string $id The identifier of the entry
     *
     * @return bool
     */
    public function isFactory($id)
    {
        return $this->has($id) && $this->entries[$id] instanceof FactoryInterface;
    }

    /**
     * Validates the entry to set.
     *
     * @param string $id The identifier of the entry
     * @param object|\Closure $value The value to set
     *
     * @throws WrongTypeException Value with wrong type given.
     * @throws \InvalidArgumentException Invalid identifier given.
     */
    protected function validateSet($id, $value): void
    {
        $this->validateName($id);

        $definition = $this->getDefinition($id);
        if (!is_object($value) || ($definition && !$definition->validate($value))) {
            throw new WrongTypeException(sprintf(
                'Value for entry "%s" must be either a factory or an object%s.',
                $id, ($definition ? ' of class '.$definition->getType() : '')
            ));
        }
    }

    /**
     * Validates the entry to remove.
     *
     * @param string $id The identifier of the entry
     *
     * @throws NotFoundException No entry was found for this identifier.
     */
    protected function validateRemove($id): void
    {
        $this->validateName($id);

        if (!isset($this->entries[$id])) {
            throw new NotFoundException(sprintf('The entry with identifier "%s" was not found.', $id));
        }
    }

    /**
     * Validates the name of an identifier.
     *
     * @param string $id The identifier
     *
     * @throws \InvalidArgumentException The identifier is invalid.
     */
    protected function validateName($id): void
    {
        if (!is_string($id)) {
            throw new \InvalidArgumentException('The entry identifier must be a string.');
        }

        if ($id === '') {
            throw new \InvalidArgumentException('The entry identifier must not be empty.');
        }
    }
}
