<?php

namespace Differ\Differ;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $diff = '{';

    $file1 = file_get_contents($pathToFile1, true);
    $data1 = json_decode($file1, true);

    $file2 = file_get_contents($pathToFile2, true);
    $data2 = json_decode($file2, true);

    $data3 = array_merge($data2, $data1);
    ksort($data3);

    foreach ($data3 as $key => $val) {
        if (!array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
            $diff = implode("\n", [$diff, "  + {$key}: {$val}"]);
        }

        if (array_key_exists($key, $data1) && !array_key_exists($key, $data2)) {
            $diff = implode("\n", [$diff, "  - {$key}: {$val}"]);
        }

        if ((array_key_exists($key, $data1) && array_key_exists($key, $data2)) && $data1[$key] == $data2[$key]) {
            $diff = implode("\n", [$diff, "    {$key}: {$val}"]);
        }

        if ((array_key_exists($key, $data1) && array_key_exists($key, $data2)) && $data1[$key] != $data2[$key]) {
            $newData = $data2[$key];
            $diff = implode("\n", [$diff, "  - {$key}: {$val}"]);
            $diff = implode("\n", [$diff, "  + {$key}: {$newData}"]);
        }
    }

    return implode("\n", [$diff, '}']);
} 