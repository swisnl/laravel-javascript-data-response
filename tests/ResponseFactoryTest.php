<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Routing\ResponseFactory as IlluminateResponseFactory;
use Orchestra\Testbench\TestCase;

class ResponseFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itMakesAResponse()
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

    /**
     * @test
     */
    public function itMakesAResponseWithDefaultNamespace()
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

    /**
     * @test
     */
    public function itMakesAResponseWithPrettyPrintOption()
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

    /**
     * @test
     */
    public function itMakesAResponseWithDefaultStatus()
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

    /**
     * @test
     */
    public function itMakesAResponseWithCustomStatus()
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

    /**
     * @test
     */
    public function itMakesAResponseWithDefaultHeaders()
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

    /**
     * @test
     */
    public function itMakesAResponseWithCustomHeaders()
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

    /**
     * @test
     */
    public function itMakesAResponseWithDefaultOptions()
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

    /**
     * @test
     */
    public function itMakesAResponseWithCustomOptions()
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
