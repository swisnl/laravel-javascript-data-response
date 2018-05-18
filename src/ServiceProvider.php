<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/' => config_path()], 'config');

        $this->registerResponseMacro($this->getResponseMacroName());
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/javascript-data-response.php', 'javascript-data-response');

        $this->app->singleton(Builder::class);
    }

    /**
     * Response macro for JavaScript data.
     *
     * @param string $name
     */
    protected function registerResponseMacro(string $name)
    {
        Response::macro(
            $name,
            function () {
                $builder = app(Builder::class);
                $factory = new ResponseFactory(/* @scrutinizer ignore-type */ $this, $builder);

                return $factory->make(...\func_get_args());
            }
        );
    }

    protected function getResponseMacroName(): string
    {
        return 'javascriptData';
    }
}
