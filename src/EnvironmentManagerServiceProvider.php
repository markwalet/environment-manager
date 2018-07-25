<?php

namespace MarkWalet\EnvironmentManager;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MarkWalet\EnvironmentManager\Adapters\FileEnvironmentAdapter;

class EnvironmentManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerManager();
        $this->registerCommands();
    }

    /**
     * Register the manager instance.
     *
     * @return void
     */
    private function registerManager()
    {
        $this->app->singleton(Environment::class, function (Application $app) {
            return new Environment(
                new FileEnvironmentAdapter($app->environmentFilePath())
            );
        });
    }

    private function registerCommands()
    {

    }
}