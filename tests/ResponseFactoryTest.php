<?php

namespace Swis\Laravel\JavaScriptData;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Routing\ResponseFactory as IlluminateResponseFactory;
use Orchestra\Testbench\TestCase;

class ResponseFactoryTest extends TestCase
{
    #[Test]
    public function itMakesAResponse(): void
    {
        /** @var \Illuminate\Routing\ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Swis\Laravel\JavaScriptData\Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['build'])
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with('namespace', [], 0)
            ->willReturn('foo bar');

        $factory = new ResponseFactory($responseFactory, $builder);
        $response = $factory->make('namespace', [], 200, [], 0);

        $this->assertSame('foo bar', $response->getContent());
    }

    #[Test]
    public function itMakesAResponseWithDefaultNamespace(): void
    {
        $this->app['config']->set('javascript-data-response.namespace', 'foo.bar');

        /** @var \Illuminate\Routing\ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Swis\Laravel\JavaScriptData\Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['build'])
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with('foo.bar.namespace');

        $factory = new ResponseFactory($responseFactory, $builder);
        $factory->make('namespace');
    }

    #[Test]
    public function itMakesAResponseWithPrettyPrintOption(): void
    {
        $this->app['config']->set('javascript-data-response.pretty-print', true);

        /** @var \Illuminate\Routing\ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Swis\Laravel\JavaScriptData\Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['build'])
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with($this->anything(), $this->anything(), JSON_PRETTY_PRINT);

        $factory = new ResponseFactory($responseFactory, $builder);
        $factory->make('namespace');
    }

    #[Test]
    public function itMakesAResponseWithDefaultStatus(): void
    {
        /** @var \Illuminate\Routing\ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Swis\Laravel\JavaScriptData\Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['build'])
            ->getMock();

        $factory = new ResponseFactory($responseFactory, $builder);
        $response = $factory->make('namespace');

        $this->assertSame(200, $response->getStatusCode());
    }

    #[Test]
    public function itMakesAResponseWithCustomStatus(): void
    {
        /** @var \Illuminate\Routing\ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Swis\Laravel\JavaScriptData\Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['build'])
            ->getMock();

        $factory = new ResponseFactory($responseFactory, $builder);
        $response = $factory->make('namespace', [], 500);

        $this->assertSame(500, $response->getStatusCode());
    }

    #[Test]
    public function itMakesAResponseWithDefaultHeaders(): void
    {
        /** @var \Illuminate\Routing\ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Swis\Laravel\JavaScriptData\Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['build'])
            ->getMock();

        $factory = new ResponseFactory($responseFactory, $builder);

        // Defaults from Symfony
        $response = $factory->make('namespace');

        $this->assertSame(2, $response->headers->count());
        $this->assertTrue($response->headers->has('cache-control'));
        $this->assertTrue($response->headers->has('date'));

        // Defaults from Symfony plus defaults from config
        $this->app['config']->set(
            'javascript-data-response.headers',
            ['Content-Type' => 'application/javascript; charset=utf-8']
        );

        $response = $factory->make('namespace');

        $this->assertSame(3, $response->headers->count());
        $this->assertTrue($response->headers->has('cache-control'));
        $this->assertTrue($response->headers->has('date'));
        $this->assertTrue($response->headers->has('content-type'));
        $this->assertSame('application/javascript; charset=utf-8', $response->headers->get('content-type'));
    }

    #[Test]
    public function itMakesAResponseWithCustomHeaders(): void
    {
        $this->app['config']->set(
            'javascript-data-response.headers',
            ['Content-Type' => 'application/javascript; charset=utf-8']
        );

        /** @var \Illuminate\Routing\ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Swis\Laravel\JavaScriptData\Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['build'])
            ->getMock();

        $factory = new ResponseFactory($responseFactory, $builder);
        $response = $factory->make('namespace', [], 200, ['X-Foo' => 'Bar']);

        $this->assertTrue($response->headers->has('x-foo'));
        $this->assertSame('Bar', $response->headers->get('x-foo'));
    }

    #[Test]
    public function itMakesAResponseWithDefaultOptions(): void
    {
        $this->app['config']->set('javascript-data-response.json_encode-options', JSON_UNESCAPED_UNICODE);

        /** @var \Illuminate\Routing\ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Swis\Laravel\JavaScriptData\Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['build'])
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with($this->anything(), $this->anything(), JSON_UNESCAPED_UNICODE);

        $factory = new ResponseFactory($responseFactory, $builder);
        $factory->make('namespace');
    }

    #[Test]
    public function itMakesAResponseWithCustomOptions(): void
    {
        /** @var \Illuminate\Routing\ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Swis\Laravel\JavaScriptData\Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['build'])
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with($this->anything(), $this->anything(), JSON_UNESCAPED_UNICODE);

        $factory = new ResponseFactory($responseFactory, $builder);
        $factory->make('namespace', [], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
