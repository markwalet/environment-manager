<?php

namespace MarkWalet\EnvironmentManager\Changes;

abstract class Change
{

    /**
     * Apply the pending change to the given content.
     *
     * @param $content
     *
     * @return mixed
     */
    public abstract function apply(string $content): string;
}