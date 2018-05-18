<?php

namespace Swis\Laravel\JavaScriptData\Stub;

use Illuminate\Contracts\Support\Arrayable;

class ArrayableStub implements Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return ['foo' => 'bar'];
    }
}
