<?php

namespace Swis\Laravel\JavaScriptData;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Routing\ResponseFactory;
use Orchestra\Testbench\TestCase;

final class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    #[Test]
    public function itMergesTheConfig(): void
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

    #[Test]
    public function itRegistersAResponseMacro(): void
    {
        $this->assertTrue(ResponseFactory::hasMacro('javascriptData'));
    }
}
