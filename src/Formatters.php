<?php

namespace Differ\Formatter;

function format(array $diffData, string $format): string
{
    var_dump($_SERVER['DOCUMENT_ROOT']);

    return match ($format) {
        default => \Differ\Formatters\stylish($diffData),
        'plain' => \Differ\Formatters\plain($diffData),
        'json' => json_encode($diffData)
    };
}
