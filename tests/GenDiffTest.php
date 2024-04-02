<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiff(): void
    {
        $expected = "{
  + about: test
  - follow: 
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: 1
}";

        $this->assertEquals($expected, genDiff('file1.json', 'file2.json'));
    }
}