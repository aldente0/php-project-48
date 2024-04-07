<?php

namespace Differ\Differ;

use Symfony\Component\Yaml\Yaml;

use function Differ\Builder\buildDiffData;

function genDiff(string $pathToFile1, string $pathToFile2,): string|null
{
    try {
        $data1 = getFileData($pathToFile1);
        $data2 = getFileData($pathToFile2);
    } catch (\Exception $e) {
        print_r($e->getMessage());
        return null;
    }

    $diffData = buildDiffData(get_object_vars($data1), get_object_vars($data2));

    $diff = '{';

    foreach ($diffData as $key => $data) {
        if ($data['status'] === 'changed') {
            $oldValue = $data['oldValue'];
            $newValue = $data['newValue'];

            $diff = implode("\n", [$diff, "  - {$key}: {$oldValue}"]);
            $diff = implode("\n", [$diff, "  + {$key}: {$newValue}"]);
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
            $diff = implode("\n", [$diff, "  - {$key}: {$value}"]);
        } elseif ($data['status'] === 'added') {
            $diff = implode("\n", [$diff, "  + {$key}: {$value}"]);
        } elseif ($data['status'] === 'not changed') {
            $diff = implode("\n", [$diff, "    {$key}: {$value}"]);
        }
    }

    return implode("\n", [$diff, '}']);
}

function getFileData(string $filePath): object
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

    if ($fileExtension === 'json') {
        return json_decode(file_get_contents($filePath));
    } elseif ($fileExtension === 'yml' || $fileExtension === 'yaml') {
        return Yaml::parseFile($filePath, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        throw new \Exception("FormatError: unsupported file format .{$fileExtension}\n");
    }
}
