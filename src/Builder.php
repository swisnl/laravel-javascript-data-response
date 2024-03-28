<?php

namespace Swis\Laravel\JavaScriptData;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Builder
{
    /**
     * @param  string  $name  The name for this data using dot notation
     * @param  mixed  $data  The data
     * @param  int  $options  Extra json_encode options
     *
     * @throws \InvalidArgumentException
     */
    public function build(string $name, $data, int $options = 0): string
    {
        // Check if we need to pretty print the results
        $prettyPrint = ($options & JSON_PRETTY_PRINT) === JSON_PRETTY_PRINT;

        // Encode to JSON
        if ($data instanceof Arrayable) {
            $json = json_encode($data->toArray(), $options);
        } elseif ($data instanceof Jsonable) {
            $json = $data->toJson($options);
        } elseif ($data instanceof \JsonSerializable) {
            $json = json_encode($data->jsonSerialize(), $options);
        } else {
            $json = json_encode($data, $options);
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg(), json_last_error());
        }

        if ($prettyPrint) {
            $json = str_replace("\n", "\n    ", $json);
        }

        // Make the JavaScript
        $namespaceParts = explode('.', $name);
        $namespace = 'window';
        $javascriptLines = [];

        foreach ($namespaceParts as $key => $namespacePart) {
            $namespace .= sprintf('["%s"]', $namespacePart);

            if ($key === \count($namespaceParts) - 1) {
                $javascriptLines[] = sprintf($prettyPrint ? '%s = %s;' : '%s=%s;', $namespace, $json);
            } else {
                $javascriptLines[] = sprintf($prettyPrint ? '%s = %s || {};' : '%s=%s||{};', $namespace, $namespace);
            }
        }

        if ($prettyPrint) {
            $javascript = sprintf("(function(){\n    %s\n})();", implode("\n    ", $javascriptLines));
        } else {
            $javascript = sprintf('(function(){%s})();', implode('', $javascriptLines));
        }

        return $javascript;
    }
}
