<?php

namespace Hexlet\Code\App;

use function Hexlet\Code\Docs\outputDocs;

function startApp(): void
{
    $argv = $_SERVER['argv'][1];
    match ($argv) {
        '-h' => outputDocs(),
    };
}
