<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $path): object
{
    if (! file_exists($path)) {
        $path = __DIR__ . "/../tests/fixtures/{$path}";
    }
    $fileExtension = pathinfo($path, PATHINFO_EXTENSION);
    if ($fileExtension === 'json') {
        return json_decode((string) file_get_contents($path));
    } elseif ($fileExtension === 'yml' || $fileExtension === 'yaml') {
        return Yaml::parseFile($path, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        throw new \Exception("FormatError: unsupported file format .{$fileExtension}\n");
    }
}
