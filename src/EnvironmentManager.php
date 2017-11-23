<?php

namespace MarkWalet\EnvironmentManager;

use MarkWalet\EnvironmentManager\Adapter\EnvironmentAdapter;
use MarkWalet\EnvironmentManager\Validator\EnvironmentValidator;

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
    }
}