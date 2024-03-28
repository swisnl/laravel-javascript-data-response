<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Http\Response;

class ResponseFactory
{
    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    private $responseFactory;

    /**
     * @var \Swis\Laravel\JavaScriptData\Builder
     */
    private $builder;

    public function __construct(ResponseFactoryContract $responseFactory, Builder $builder)
    {
        $this->responseFactory = $responseFactory;
        $this->builder = $builder;
    }

    /**
     * Return a new JavaScript data response from the application.
     *
     * @param  mixed  $data
     * @param  int  $options
     */
    public function make(string $name, $data = [], int $status = 200, array $headers = [], $options = 0): Response
    {
        $name = ltrim(config('javascript-data-response.namespace', '').'.'.$name, '.');

        if (empty($headers)) {
            $headers = (array) config('javascript-data-response.headers', []);
        }

        if ($options === 0) {
            $options = (int) config('javascript-data-response.json_encode-options', 0);
        }

        if (config('javascript-data-response.pretty-print', false)) {
            $options |= JSON_PRETTY_PRINT;
        }

        return $this->responseFactory->make(
            $this->builder->build($name, $data, $options),
            $status,
            $headers
        );
    }
}
