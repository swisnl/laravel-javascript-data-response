<?php

namespace Swis\Laravel\JavaScriptData\Tests\Stubs;

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
