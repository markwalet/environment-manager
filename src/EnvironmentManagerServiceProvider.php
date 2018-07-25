<?php

namespace MarkWalet\EnvironmentManager;

use Illuminate\Support\ServiceProvider;
use MarkWalet\EnvironmentManager\Adapters\FileEnvironmentAdapter;
use MarkWalet\EnvironmentManager\Commands\AddEnvironmentValueCommand;
use MarkWalet\EnvironmentManager\Commands\RemoveEnvironmentValueCommand;
use MarkWalet\EnvironmentManager\Commands\SetEnvironmentValueCommand;

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
        $this->app->singleton(Environment::class, function () {
            return new Environment(
                new FileEnvironmentAdapter(base_path('.env'))
            );
        });
    }
}