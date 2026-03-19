<?php

namespace Swis\Laravel\JavaScriptData\Tests\Stubs;

use Illuminate\Contracts\Support\Jsonable;

class JsonableStub implements Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return '{"foo":"bar"}';
    }
}
