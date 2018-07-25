<?php

namespace MarkWalet\EnvironmentManager;

use Closure;
use MarkWalet\EnvironmentManager\Exceptions\InvalidArgumentException;
use MarkWalet\EnvironmentManager\Adapters\EnvironmentAdapter;

/**
 * Class Environment
 * @package MarkWalet\Environment
 *
 * @method bool add(string $key, $value = null)
 * @method bool create(string $key, $value = null)
 * @method bool set(string $key, $value = null)
 * @method bool update(string $key, $value = null)
 * @method bool move(string $key)
 * @method bool delete(string $key)
 * @method bool unset(string $key)
 */
class Environment
{
    /**
     * @var EnvironmentAdapter
     */
    private $adapter;

    /**
     * @var EnvironmentBuilder
     */
    private $builder;

    /**
     * Environment constructor.
     *
     * @param EnvironmentAdapter $adapter
     */
    function __construct(EnvironmentAdapter $adapter)
    {
        $this->adapter = $adapter;
        $this->builder = new EnvironmentBuilder;
    }

    /**
     * Update and persist the environment file.
     *
     * @param Closure $callback
     *
     * @return bool
     */
    public function mutate(Closure $callback)
    {
        $callback($this->builder);

        return $this->persist();
    }

    /**
     * Extend the builder with a new method.
     *
     * @param string $method Name of the method
     * @param string $class Class name that implements PendingChange
     * @throws InvalidArgumentException
     */
    public function extend(string $method, string $class)
    {
        $this->builder->extend($method, $class);
    }

    /**
     * Persist pending changes to the environment file.
     *
     * @return bool
     */
    private function persist()
    {
        $content = $this->adapter->read();

        $content = $this->builder->apply($content);

        return $this->adapter->write($content);
    }

    /**
     * Call a change action dynamically.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return bool
     */
    public function __call($method, $parameters)
    {
        // Call method on builder.
        $this->builder->$method(...$parameters);

        // Persist changes.
        return $this->persist();
    }
}