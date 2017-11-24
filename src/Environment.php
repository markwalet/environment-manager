<?php

namespace MarkWalet\EnvironmentManager;

use MarkWalet\EnvironmentManager\Exceptions\InvalidArgumentException;

class Environment
{
    /**
     * @var EnvironmentLine[]
     */
    public $lines = [];

    /**
     * Get the index for a given key.
     *
     * Returns -1 if the key is not present.
     * @param string $key
     *
     * @return int
     */
    public function indexForKey(string $key)
    {
        foreach($this->lines as $index => $line) {
            if ($line->key() === $key) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * Determine if the environment contains a given key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key)
    {
        foreach($this->lines as $index => $line) {
            if ($line->key() === $key) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a environment line.
     *
     * @param EnvironmentLine $line
     */
    public function add(EnvironmentLine $line)
    {
        $this->lines[] = $line;
    }

    /**
     * Whether a offset exists
     *
     * @param boolean $offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->lines);
    }

    /**
     * Offset to retrieve
     *
     * @param string $offset
     *
     * @return EnvironmentLine
     */
    public function offsetGet($offset)
    {
        return $this->lines[$offset];
    }

    /**
     * Offset to set
     *
     * @param string $offset
     * @param EnvironmentLine $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if (is_object($value) === false || get_class($value) !== EnvironmentLine::class) {
            throw new InvalidArgumentException("Value is not a environment line.");
        }

        $this->lines[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->lines[$offset]);
    }
}