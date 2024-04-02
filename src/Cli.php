<?php

namespace Differ\Differ\Cli;

use function Differ\Differ\outputDocs;
use function Differ\Differ\genDiff;

function startApp(): void
{
    $first = $_SERVER['argv'][1];
    $second = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : null;

    match ($first) {
        '-h' => outputDocs(),
        default => print_r(genDiff($first, $second))
    };
}
