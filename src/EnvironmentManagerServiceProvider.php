<?php

namespace MarkWalet\EnvironmentManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;
use MarkWalet\EnvironmentManager\Adapter\EnvironmentAdapter;
use MarkWalet\EnvironmentManager\Adapter\FileEnvironmentAdapter;
use MarkWalet\EnvironmentManager\Validator\LaravelEnvironmentValidator;
use MarkWalet\EnvironmentManager\Validator\EnvironmentValidator;

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
        $this->registerAdapter();

        $this->registerValidator();

        $this->registerManager();
    }


    /**
     * Register the adapter instance.
     *
     * @return void
     */
    protected function registerAdapter()
    {
        $this->app->singleton(EnvironmentAdapter::class, FileEnvironmentAdapter::class);
    }

    /**
     * Register the validator instance.
     *
     * @return void
     */
    protected function registerValidator()
    {
        $this->app->singleton(EnvironmentValidator::class, LaravelEnvironmentValidator::class);
    }

    /**
     * Register the manager instance.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton(EnvironmentManager::class, function(Application $app) {
            return new EnvironmentManager(
                $app->make(EnvironmentAdapter::class),
                $app->make(EnvironmentValidator::class)
            );
        });
    }
}