<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Support\ServiceProvider;

class JavaScriptDataServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/' => config_path()], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/javascript-data-response.php', 'javascript-data-response');
    }
}
