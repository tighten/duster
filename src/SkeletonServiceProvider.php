<?php

namespace Tightenco\:package_php_namespace;

use Illuminate\Support\ServiceProvider;

class :package_php_namespaceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path(':package_name.php'),
            ], 'config');

            /*
            $this->loadViewsFrom(__DIR__.'/../resources/views', ':package_name');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/:package_name'),
            ], 'views');
            */
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', ':package_name');
    }
}
