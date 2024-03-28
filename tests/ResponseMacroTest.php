<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Support\Facades\Response;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ResponseMacroTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('javascript-data-response.pretty-print', false);
    }

    #[Test]
    public function itMakesAResponse(): void
    {
        /** @var \Illuminate\Http\Response $response */
        $response = Response::javascriptData('namespace', ['foo' => 'bar']);

        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
        $this->assertSame('(function(){window["namespace"]={"foo":"bar"};})();', $response->getContent());
    }
}
