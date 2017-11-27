<?php

namespace MarkWalet\EnvironmentManager\Changes;

use MarkWalet\EnvironmentManager\Changes\Concerns\HasKey;
use MarkWalet\EnvironmentManager\Exceptions\InvalidPositionException;

class Move extends Change
{
    use HasKey;

    /**
     * @var string
     */
    private $after;

    /**
     * @var string
     */
    private $before;

    /**
     * Move constructor.
     *
     * @param string $key
     */
    function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Apply the pending change to the given content.
     *
     * @param string $content
     *
     * @return string
     * @throws InvalidPositionException
     */
    public function apply(string $content): string
    {
        preg_match('/'.$this->getKey().'=(.*)/', $content, $matches);
        $value = $matches[1];

        $content = (new Delete($this->getKey()))->apply($content);

        if ($this->after) {
            return (new Addition($this->getKey(), $value))->after($this->after)->apply($content);
        }

        if ($this->before) {
            return (new Addition($this->getKey(), $value))->before($this->before)->apply($content);
        }

        throw new InvalidPositionException();
    }

    /**
     * Place new value after a key.
     *
     * @param string $key
     *
     * @return Change
     */
    public function after(string $key): Change
    {
        $this->before = null;
        $this->after = $key;

        return $this;
    }

    /**
     * Place new value before a key.
     *
     * @param string $key
     *
     * @return Change
     */
    public function before(string $key): Change
    {
        $this->after = null;
        $this->before = $key;

        return $this;
    }
}