<?php

namespace MarkWalet\EnvironmentManager;

class EnvironmentLine
{
    /**
     * @var string
     */
    private $key;

    /**
     * The value of the environment line.
     *
     * @var mixed
     */
    private $value;

    /**
     * EnvironmentLine constructor.
     *
     * @param string $key
     * @param $value
     */
    public function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = is_string($value) ? $this->valueFromString($value) : $value;
    }

    /**
     * Get the key of the environment line.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * Get the value of the environment line.
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Cast string value to primitive type.
     *
     * @param string $value
     *
     * @return bool|null|string
     */
    private function valueFromString(string $value)
    {
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return (strlen($value) > 1 && substr($value, 0, 1) === '"' && substr($value, -1) === '"')
            ? substr($value, 1, -1)
            : $value;
    }

    /**
     * Cast value to string.
     *
     * @param $value
     *
     * @return string
     */
    private function valueToString($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_null($value)) {
            return 'null';
        }

        if (preg_match('/\s/', $value)) {
            return "\"$value\"";
        }

        return $value;
    }

    /**
     * Cast environment line to string.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->key === null && $this->value === null) {
            return "";
        }

        return $this->key().'='.$this->valueToString($this->value());
    }
}