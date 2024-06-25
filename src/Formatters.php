<?php

namespace Differ\Formatter;

function format(array $diffData, string $format): string
{
    return match ($format) {
        default => \Differ\Formatters\stylish($diffData),
        'plain' => \Differ\Formatters\plain($diffData),
        'json' => \Differ\Formatters\json($diffData)
    };
}
