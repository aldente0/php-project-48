<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $path): object
{
    $curPath = ! file_exists($path) ? __DIR__ . "/../tests/fixtures/{$path}" : $path;
    $fileExtension = pathinfo($curPath, PATHINFO_EXTENSION);
    if ($fileExtension === 'json') {
        return json_decode((string) file_get_contents($curPath));
    } elseif ($fileExtension === 'yml' || $fileExtension === 'yaml') {
        return Yaml::parseFile($curPath, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        throw new \Exception("FormatError: unsupported file format .{$fileExtension}\n");
    }
}
