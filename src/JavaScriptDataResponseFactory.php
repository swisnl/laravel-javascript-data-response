<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class JavaScriptDataResponseFactory
{
    /**
     * @var \Illuminate\Routing\ResponseFactory
     */
    private $responseFactory;

    /**
     * @var \Swis\Laravel\JavaScriptData\JavaScriptDataBuilder
     */
    private $javaScriptDataBuilder;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory      $responseFactory
     * @param \Swis\Laravel\JavaScriptData\JavaScriptDataBuilder $javaScriptDataBuilder
     */
    public function __construct(ResponseFactory $responseFactory, JavaScriptDataBuilder $javaScriptDataBuilder)
    {
        $this->responseFactory = $responseFactory;
        $this->javaScriptDataBuilder = $javaScriptDataBuilder;
    }

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
    public function make(string $name, $data = [], int $status = 200, array $headers = [], $options = 0): Response
    {
        $name = ltrim(config('javascript-data-response.namespace', '').'.'.$name, '.');

        if (empty($headers)) {
            $headers = (array)config('javascript-data-response.headers', []);
        }

        if ($options === 0) {
            $options = (int)config('javascript-data-response.json_encode-options', 0);
        }

        if (config('javascript-data-response.pretty-print', false)) {
            $options |= JSON_PRETTY_PRINT;
        }

        return $this->responseFactory->make(
            $this->javaScriptDataBuilder->build($name, $data, $options),
            $status,
            $headers
        );
    }
}
