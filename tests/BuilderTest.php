<?php

namespace Swis\Laravel\JavaScriptData\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Swis\Laravel\JavaScriptData\Builder;
use Swis\Laravel\JavaScriptData\Tests\Stubs\ArrayableStub;
use Swis\Laravel\JavaScriptData\Tests\Stubs\JsonableStub;
use Swis\Laravel\JavaScriptData\Tests\Stubs\JsonSerializableStub;

final class BuilderTest extends TestCase
{
    #[Test]
    public function it_builds(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', ['foo' => 'bar']);

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function it_builds_a_nested_namespace(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('name.space.string', ['foo' => 'bar']);

        $this->assertEquals(
            '(function(){window["name"]=window["name"]||{};window["name"]["space"]=window["name"]["space"]||{};window["name"]["space"]["string"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function it_builds_with_extra_json_encode_options(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', ['test' => 'tést/tëst', 'foo' => []], JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

        $this->assertEquals(
            '(function(){window["namespace"]={"test":"tést\/tëst","foo":{}};})();',
            $javascript
        );
    }

    #[Test]
    public function it_builds_pretty_printed(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('name.space', ['foo' => 'bar'], JSON_PRETTY_PRINT);

        $this->assertEquals(
            str_replace("\r\n", "\n", '(function(){
    window["name"] = window["name"] || {};
    window["name"]["space"] = {
        "foo": "bar"
    };
})();'),
            $javascript
        );
    }

    #[Test]
    public function it_builds_with_arrayable_data(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', new ArrayableStub);

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function it_builds_with_jsonable_data(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', new JsonableStub);

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function it_builds_with_json_serializable_data(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', new JsonSerializableStub);

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function it_throws_when_json_encode_failed(): void
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Malformed UTF-8 characters, possibly incorrectly encoded', 5));

        $builder = new Builder;
        $builder->build('namespace', "\x80");
    }
}
