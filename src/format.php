<?php

namespace Differ\Formatter;

function format(array $diffData, string $format): string
{
    if ($format === 'stylish') {
        return \Differ\Formatters\stylish($diffData);
    } else {
        return \Differ\Formatters\stylish($diffData);
    }
}
