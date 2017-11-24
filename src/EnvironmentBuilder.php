<?php

namespace MarkWalet\EnvironmentManager;

use MarkWalet\EnvironmentManager\Changes\Addition;
use MarkWalet\EnvironmentManager\Changes\Change;
use MarkWalet\EnvironmentManager\Changes\Delete;
use MarkWalet\EnvironmentManager\Changes\Update;
use MarkWalet\EnvironmentManager\Exceptions\InvalidArgumentException;
use MarkWalet\EnvironmentManager\Exceptions\MethodNotFoundException;

/**
 * Class EnvironmentBuilder
 * @package MarkWalet\EnvironmentManager
 *
 * @method Addition add(string $key, $value = null)
 * @method Update set(string $key, $value = null)
 * @method Delete delete(string $key)
 * @method Delete unset(string $key)
 *
 */
class EnvironmentBuilder
{
    /**
     * @var array
     */
    private $methods = [
        'add' => Addition::class,
        'set' => Update::class,
        'delete' => Delete::class,
        'unset' => Delete::class,
    ];

    /**
     * A list of pending changes.
     *
     * @var Change[]
     */
    private $changes = [];

    /**
     * Add a pending change.
     *
     * @param Change $change
     *
     * @return Change
     */
    public function change(Change $change): Change
    {
        return $this->changes[] = $change;
    }

    /**
     * Apply changes to the given content.
     *
     * @param string $content
     *
     * @return string
     */
    public function apply(string $content)
    {
        /** @var Change $change */
        foreach($this->changes as $change) {
            $content = $change->apply($content);
        }

        return $content;
    }

    /**
     * Call a change action dynamically.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return Change
     * @throws MethodNotFoundException
     */
    public function __call($method, $parameters)
    {
        // Check if requested method is registered as action.
        if (array_key_exists($method, $this->methods) === false) {
            throw new MethodNotFoundException();
        }

        // Instantiate new change object.
        $change = new $this->methods[$method](...$parameters);

        // Add change to builder.
        return $this->change($change);
    }

    /**
     * Extend the builder with a new method.
     *
     * @param string $method Name of the method
     * @param string $class Class name that implements PendingChange
     *
     * @throws InvalidArgumentException
     */
    public function extend(string $method, string $class)
    {
        if (class_exists($class) === false) {
            throw new InvalidArgumentException("Class {$class} is not found.");
        }

        if (is_subclass_of($class, Change::class)) {
            throw new InvalidArgumentException("{$class} does not extend " . Change::class);
        }

        $this->methods[$method] = $class;
    }
}