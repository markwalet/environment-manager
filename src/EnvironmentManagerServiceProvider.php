<?php

namespace MarkWalet\EnvironmentManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;
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
    }

    /**
     * Register the manager instance.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton(Environment::class, function(Application $app) {
            return new Environment(
                new FileEnvironmentAdapter($app->environmentFilePath())
            );
        });
    }
}