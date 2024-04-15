<?php

namespace Differ\Differ;

use function Differ\Builder\buildDiffData;
use function Differ\Formatter\format;
use function Differ\Parser\parseFile;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string|null
{
    try {
        $data1 = parseFile($pathToFile1);
        $data2 = parseFile($pathToFile2);
        print_r($data1);
    } catch (\Exception $e) {
        print_r($e->getMessage());
        return null;
    }

    $diffData = buildDiffData($data1, $data2);

    return format($diffData, $format);
}
