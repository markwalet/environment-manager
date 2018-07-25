<?php

namespace MarkWalet\EnvironmentManager\Commands;

use Illuminate\Console\Command;
use MarkWalet\EnvironmentManager\Environment;

class SetEnvironmentValueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:set {key} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a value from the `.env` file.';

    /**
     * The Environment instance.
     *
     * @var Environment
     */
    private $env;

    /**
     * Create a new command instance.
     *
     * @param Environment $env
     */
    public function __construct(Environment $env)
    {
        parent::__construct();

        $this->env = $env;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->env->set(
            $this->argument('key'),
            $this->argument('value')
        );
    }
}