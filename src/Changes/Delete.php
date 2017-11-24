<?php

namespace MarkWalet\EnvironmentManager\Changes;

use MarkWalet\EnvironmentManager\Changes\Concerns\HasKey;

class Delete extends Change
{
    use HasKey;

    /**
     * PendingDelete constructor.
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
     * @param $content
     *
     * @return string
     */
    public function apply(string $content): string
    {
        $pattern = '(?:'.$this->getKey().'=.*)';

        return preg_replace(['/'.$pattern.'(\R)/', '/(\R)'.$pattern.'/'], '', $content);
    }
}