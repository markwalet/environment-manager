<?php

namespace MarkWalet\EnvironmentManager\Adapters;

interface EnvironmentAdapter
{
    /**
     * Get the contents of the environment file.
     *
     * @return string
     */
    public function read(): string;

    /**
     * Write a string to the environment file.
     *
     * @param string $content
     *
     * @return bool
     */
    public function write(string $content): bool;
}