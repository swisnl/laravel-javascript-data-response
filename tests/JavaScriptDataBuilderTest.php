<?php

namespace Swis\Laravel\JavaScriptData;

use PHPUnit\Framework\TestCase;
use Swis\Laravel\JavaScriptData\Stub\ArrayableStub;
use Swis\Laravel\JavaScriptData\Stub\JsonableStub;
use Swis\Laravel\JavaScriptData\Stub\JsonSerializableStub;

class JavaScriptDataBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function itBuilds()
    {
        $builder = new JavaScriptDataBuilder();
        $javascript = $builder->build('namespace', ['foo' => 'bar']);

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    /**
     * @test
     */
    public function itBuildsANestedNamespace()
    {
        $builder = new JavaScriptDataBuilder();
        $javascript = $builder->build('name.space.string', ['foo' => 'bar']);

        $this->assertEquals(
            '(function(){window["name"]=window["name"]||{};window["name"]["space"]=window["name"]["space"]||{};window["name"]["space"]["string"]={"foo":"bar"};})();',
            $javascript
        );
    }

    /**
     * @test
     */
    public function itBuildsWithExtraJsonEncodeOptions()
    {
        $builder = new JavaScriptDataBuilder();
        $javascript = $builder->build('namespace', ['test' => 'tést/tëst', 'foo' => []], JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

        $this->assertEquals(
            '(function(){window["namespace"]={"test":"tést\/tëst","foo":{}};})();',
            $javascript
        );
    }

    /**
     * @test
     */
    public function itBuildsPrettyPrinted()
    {
        $builder = new JavaScriptDataBuilder();
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

    /**
     * @test
     */
    public function itBuildsWithArrayableData()
    {
        $builder = new JavaScriptDataBuilder();
        $javascript = $builder->build('namespace', new ArrayableStub());

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    /**
     * @test
     */
    public function itBuildsWithJsonableData()
    {
        $builder = new JavaScriptDataBuilder();
        $javascript = $builder->build('namespace', new JsonableStub());

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    /**
     * @test
     */
    public function itBuildsWithJsonSerializableData()
    {
        $builder = new JavaScriptDataBuilder();
        $javascript = $builder->build('namespace', new JsonSerializableStub());

        $this->assertEquals(
            '(function(){window["namespace"]={"foo":"bar"};})();',
            $javascript
        );
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 5
     * @expectedExceptionMessage Malformed UTF-8 characters, possibly incorrectly encoded
     */
    public function itThrowsWhenJsonEncodeFailed()
    {
        $builder = new JavaScriptDataBuilder();
        $builder->build('namespace', "\x80");
    }
}
