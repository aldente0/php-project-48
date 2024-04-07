<?php

namespace Differ\Differ;

use function Differ\Builder\buildDiffData;
use function Differ\Parser\parseFile;

function genDiff(string $pathToFile1, string $pathToFile2,): string|null
{
    try {
        $data1 = parseFile($pathToFile1);
        $data2 = parseFile($pathToFile2);
    } catch (\Exception $e) {
        print_r($e->getMessage());
        return null;
    }

    $diffData = buildDiffData(get_object_vars($data1), get_object_vars($data2));

    $diff = '{';

    foreach ($diffData as $data) {
        $name = $data['name'];
        if ($data['status'] === 'changed') {
            $oldValue = $data['oldValue'];
            $newValue = $data['newValue'];

            $diff = implode("\n", [$diff, "  - {$name}: {$oldValue}"]);
            $diff = implode("\n", [$diff, "  + {$name}: {$newValue}"]);
        }

        $value = $data['value'];
        if (is_bool($value)) {
            if ($value === true) {
                $value = 'true';
            } else {
                $value = 'false';
            }
        }

        if ($data['status'] === 'deleted') {
            $diff = implode("\n", [$diff, "  - {$name}: {$value}"]);
        } elseif ($data['status'] === 'added') {
            $diff = implode("\n", [$diff, "  + {$name}: {$value}"]);
        } elseif ($data['status'] === 'not changed') {
            $diff = implode("\n", [$diff, "    {$name}: {$value}"]);
        }
    }

    return implode("\n", [$diff, '}']);
}
