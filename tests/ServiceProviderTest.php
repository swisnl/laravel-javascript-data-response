<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Routing\ResponseFactory;
use Orchestra\Testbench\TestCase;

class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * @test
     */
    public function itMergesTheConfig()
    {
        $this->assertSame(
            [
                'namespace' => '',
                'json_encode-options' => JSON_UNESCAPED_UNICODE,
                'pretty-print' => env('APP_ENV') !== 'production',
                'headers' => [
                    'Content-Type' => 'application/javascript; charset=utf-8',
                ],
            ],
            $this->app['config']->get('javascript-data-response')
        );
    }

    /**
     * @test
     */
    public function itRegistersAResponseMacro()
    {
        $this->assertTrue(ResponseFactory::hasMacro('javascriptData'));
    }
}
