<?php

namespace Differ\Differ;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $diff = '{';

    $data1 = json_decode(file_get_contents($pathToFile1), true) ?? [];
    $data2 = json_decode(file_get_contents($pathToFile2), true) ?? [];

    $added = array_diff($data2, $data1);
    $removed = array_diff($data1, $data2);
    $merged = array_merge($data2, $data1);
    ksort($merged);

    foreach ($merged as $key => $val) {
        if (isset($removed[$key])) {
            $diff = implode("\n", [$diff, "  - {$key}: {$val}"]);
        }

        if (isset($added[$key])) {
            $newData = $data2[$key];
            $diff = implode("\n", [$diff, "  + {$key}: {$newData}"]);
        }

        if (! isset($added[$key]) && ! isset($removed[$key])) {
            $diff = implode("\n", [$diff, "    {$key}: {$val}"]);
        }
    }

    return implode("\n", [$diff, '}']);
}
