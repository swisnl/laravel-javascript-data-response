# Laravel JavaScript data response

[![PHP from Packagist](https://img.shields.io/packagist/php-v/swisnl/laravel-javascript-data-response.svg)](https://packagist.org/packages/swisnl/laravel-javascript-data-response)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/swisnl/laravel-javascript-data-response.svg)](https://packagist.org/packages/swisnl/laravel-javascript-data-response)
[![Software License](https://img.shields.io/packagist/l/swisnl/laravel-javascript-data-response.svg)](LICENSE)
[![Build Status](https://travis-ci.org/swisnl/laravel-javascript-data-response.svg?branch=master)](https://travis-ci.org/swisnl/laravel-javascript-data-response)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/swisnl/laravel-javascript-data-response.svg)](https://scrutinizer-ci.com/g/swisnl/laravel-javascript-data-response/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/swisnl/laravel-javascript-data-response.svg)](https://scrutinizer-ci.com/g/swisnl/laravel-javascript-data-response/?branch=master)
[![Made by SWIS](https://img.shields.io/badge/%F0%9F%9A%80-made%20by%20SWIS-%23D9021B.svg)](https://www.swis.nl)

JavaScript data response macro for Laravel

## Install

``` bash
$ composer require swisnl/laravel-javascript-data-response
```

## Usage

This package adds a response macro (similar to Response::jsonp) which you can use just like any other response e.g.

```php
Response::javascriptData('translations', ['en' => ['foo' => 'bar']]);
// or
response()->javascriptData('translations', ['en' => ['foo' => 'bar']]);
```

This will create the following response with the appropriate headers:

```javascript
(function(){
    window["translations"] = {
        "en": {
            "foo": "bar"
        }
    };
})();
```

## Configuration

The following is the default configuration provided by this package:

``` php
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
    | These will be ignored if options are provided
    | to the response macro/factory.
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
    | These will be ignored if headers are provided
    | to the response macro/factory.
    |
    */

    'headers' => [
        'Content-Type' => 'application/javascript; charset=utf-8',
    ],
];
```

### Publish Configuration

If you would like to make changes to the default configuration, publish and edit the configuration file:

``` bash
php artisan vendor:publish --provider="Swis\Laravel\JavaScriptData\ServiceProvider" --tag="config"
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email security@swis.nl instead of using the issue tracker.

## Credits

- [Jasper Zonneveld](https://github.com/JaZo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## SWIS

[SWIS](https://www.swis.nl) is a web agency from Leiden, the Netherlands. We love working with open source software.
