<?php

namespace Swis\Laravel\JavaScriptData;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Swis\Laravel\JavaScriptData\Stub\ArrayableStub;
use Swis\Laravel\JavaScriptData\Stub\JsonableStub;
use Swis\Laravel\JavaScriptData\Stub\JsonSerializableStub;

final class BuilderTest extends TestCase
{
    #[Test]
    public function itBuilds(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', ['foo' => 'bar']);

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function itBuildsANestedNamespace(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('name.space.string', ['foo' => 'bar']);

        $this->assertEquals(
            '(function(){window["name"]=window["name"]||{};window["name"]["space"]=window["name"]["space"]||{};window["name"]["space"]["string"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function itBuildsWithExtraJsonEncodeOptions(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', ['test' => 'tést/tëst', 'foo' => []], JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

        $this->assertEquals(
            '(function(){window["namespace"]={"test":"tést\/tëst","foo":{}};})();',
            $javascript
        );
    }

    #[Test]
    public function itBuildsPrettyPrinted(): void
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
    public function itBuildsWithArrayableData(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', new ArrayableStub);

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function itBuildsWithJsonableData(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', new JsonableStub);

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function itBuildsWithJsonSerializableData(): void
    {
        $builder = new Builder;
        $javascript = $builder->build('namespace', new JsonSerializableStub);

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    #[Test]
    public function itThrowsWhenJsonEncodeFailed(): void
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Malformed UTF-8 characters, possibly incorrectly encoded', 5));

        $builder = new Builder;
        $builder->build('namespace', "\x80");
    }
}
