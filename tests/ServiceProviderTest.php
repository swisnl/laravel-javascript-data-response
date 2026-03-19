<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Routing\ResponseFactory;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    #[Test]
    public function it_merges_the_config(): void
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
    public function it_registers_a_response_macro(): void
    {
        $this->assertTrue(ResponseFactory::hasMacro('javascriptData'));
    }
}
