<?php

namespace Differ\Differ;

use Symfony\Component\Yaml\Yaml;

function genDiff(string $pathToFile1, string $pathToFile2): string|null
{
    $diff = '{';
    try {
        $data1 = getFileData($pathToFile1);
        $data2 = getFileData($pathToFile2);
    } catch (\Exception $e) {
        print_r($e->getMessage());
        return null;
    }

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

function getFileData(string $filePath): array|object
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

    if ($fileExtension === 'json') {
        return json_decode(file_get_contents($filePath), true) ?? [];
    } else if ($fileExtension === 'yml' || $fileExtension === 'yaml') {
        return Yaml::parseFile($filePath, /*Yaml::PARSE_OBJECT_FOR_MAP*/);
    } else {
        throw new \Exception("FormatError: unsupported file format .{$fileExtension}\n");
    }
}
