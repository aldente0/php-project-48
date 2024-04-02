<?php

require_once __DIR__ . "/../vendor/autoload.php";

use function Differ\Differ\genDiff;

$diff = genDiff('file1.json', 'file2.json');

print_r($diff);