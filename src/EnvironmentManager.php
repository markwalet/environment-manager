<?php

namespace MarkWalet\EnvironmentManager;

use Closure;
use MarkWalet\EnvironmentManager\Adapters\EnvironmentAdapter;
use MarkWalet\EnvironmentManager\Exceptions\MethodNotFoundException;
use MarkWalet\EnvironmentManager\Validator\EnvironmentValidator;

/**
 * Class EnvironmentManager
 * @package MarkWalet\EnvironmentManager
 *
 * @method bool add(string $key, $value = null)
 * @method bool set(string $key, $value = null)
 * @method bool delete(string $key)
 * @method bool unset(string $key)
 */
class EnvironmentManager
{
    /**
     * @var EnvironmentAdapter
     */
    private $adapter;

    /**
     * @var EnvironmentValidator
     */
    private $validator;
    /**
     * EnvironmentManager constructor.
     *
     * @param EnvironmentAdapter $adapter
     * @param EnvironmentValidator $validator
     */
    function __construct(EnvironmentAdapter $adapter, EnvironmentValidator $validator)
    {
        $this->adapter = $adapter;
        $this->validator = $validator;
        $this->builder = new EnvironmentBuilder;
    }

    /**
     * Update and persist the environment file.
     *
     * @param Closure $callback
     *
     * @return bool
     */
    public function update(Closure $callback)
    {
        $callback($this->builder);

        return $this->persist();
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
     * @throws MethodNotFoundException
     */
    public function __call($method, $parameters)
    {
        // Call method on builder.
        $this->builder->$method(...$parameters);

        // Persist changes.
        return $this->persist();
    }
}