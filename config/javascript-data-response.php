<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JavaScript data Namespace
    |--------------------------------------------------------------------------
    |
    | The namespace to use for the JavaScript data using dot notation e.g. foo.bar will result in window["foo"]["bar"].
    |
    */

    'namespace' => '',

    /*
    |--------------------------------------------------------------------------
    | Default json_encode options
    |--------------------------------------------------------------------------
    |
    | The default options to use when json_encoding the data.
    |
    */

    'json_encode-options' => JSON_UNESCAPED_UNICODE,

    /*
    |--------------------------------------------------------------------------
    | Pretty print
    |--------------------------------------------------------------------------
    |
    | Should we add JSON_PRETTY_PRINT to the json_encode options.
    |
    */

    'pretty-print' => env('APP_ENV') !== 'production',

    /*
    |--------------------------------------------------------------------------
    | Default response headers
    |--------------------------------------------------------------------------
    |
    | The default headers for the JavaScript data response.
    |
    */

    'headers' => [
        'Content-Type' => 'application/javascript; charset=utf-8',
    ],
];
