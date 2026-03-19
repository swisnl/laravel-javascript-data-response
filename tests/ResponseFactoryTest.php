<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Routing\ResponseFactory as IlluminateResponseFactory;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;

final class ResponseFactoryTest extends TestCase
{
    #[Test]
    public function it_makes_a_response(): void
    {
        /** @var IlluminateResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var MockObject&Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['build'])
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
    public function it_makes_a_response_with_default_namespace(): void
    {
        $this->app['config']->set('javascript-data-response.namespace', 'foo.bar');

        /** @var IlluminateResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var MockObject&Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['build'])
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with('foo.bar.namespace');

        $factory = new ResponseFactory($responseFactory, $builder);
        $factory->make('namespace');
    }

    #[Test]
    public function it_makes_a_response_with_pretty_print_option(): void
    {
        $this->app['config']->set('javascript-data-response.pretty-print', true);

        /** @var IlluminateResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var MockObject&Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['build'])
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with($this->anything(), $this->anything(), JSON_PRETTY_PRINT);

        $factory = new ResponseFactory($responseFactory, $builder);
        $factory->make('namespace');
    }

    #[Test]
    public function it_makes_a_response_with_default_status(): void
    {
        /** @var IlluminateResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var MockObject&Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['build'])
            ->getMock();

        $factory = new ResponseFactory($responseFactory, $builder);
        $response = $factory->make('namespace');

        $this->assertSame(200, $response->getStatusCode());
    }

    #[Test]
    public function it_makes_a_response_with_custom_status(): void
    {
        /** @var IlluminateResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var MockObject&Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['build'])
            ->getMock();

        $factory = new ResponseFactory($responseFactory, $builder);
        $response = $factory->make('namespace', [], 500);

        $this->assertSame(500, $response->getStatusCode());
    }

    #[Test]
    public function it_makes_a_response_with_default_headers(): void
    {
        /** @var IlluminateResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var MockObject&Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['build'])
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
    public function it_makes_a_response_with_custom_headers(): void
    {
        $this->app['config']->set(
            'javascript-data-response.headers',
            ['Content-Type' => 'application/javascript; charset=utf-8']
        );

        /** @var IlluminateResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var MockObject&Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['build'])
            ->getMock();

        $factory = new ResponseFactory($responseFactory, $builder);
        $response = $factory->make('namespace', [], 200, ['X-Foo' => 'Bar']);

        $this->assertTrue($response->headers->has('x-foo'));
        $this->assertSame('Bar', $response->headers->get('x-foo'));
    }

    #[Test]
    public function it_makes_a_response_with_default_options(): void
    {
        $this->app['config']->set('javascript-data-response.json_encode-options', JSON_UNESCAPED_UNICODE);

        /** @var IlluminateResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var MockObject&Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['build'])
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with($this->anything(), $this->anything(), JSON_UNESCAPED_UNICODE);

        $factory = new ResponseFactory($responseFactory, $builder);
        $factory->make('namespace');
    }

    #[Test]
    public function it_makes_a_response_with_custom_options(): void
    {
        /** @var IlluminateResponseFactory $responseFactory */
        $responseFactory = $this->app->make(IlluminateResponseFactory::class);
        /** @var MockObject&Builder $builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['build'])
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with($this->anything(), $this->anything(), JSON_UNESCAPED_UNICODE);

        $factory = new ResponseFactory($responseFactory, $builder);
        $factory->make('namespace', [], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
