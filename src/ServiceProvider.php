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
            /**
             * Return a new JavaScript data response from the application.
             *
             * @param string $name
             * @param mixed  $data
             * @param int    $status
             * @param array  $headers
             * @param int    $options
             *
             * @return \Illuminate\Http\Response
             */
            function (string $name, $data = [], int $status = 200, array $headers = [], $options = 0) {
                $builder = app(Builder::class);
                $factory = new ResponseFactory(/* @scrutinizer ignore-type */ $this, $builder);

                return $factory->make($name, $data, $status, $headers, $options);
            }
        );
    }

    protected function getResponseMacroName(): string
    {
        return 'javascriptData';
    }
}
