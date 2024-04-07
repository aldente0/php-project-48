<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;
use function Differ\Differ\getFileData;

class GenDiffTest extends TestCase
{
    public string $file1 = 'tests/fixtures/file1.json';
    public string $file2 = 'tests/fixtures/file2.json';
    public function testGenDiff(): void
    {
        $expected = file_get_contents('fixtures/simpleRes.txt', true);

        $this->assertEquals($expected, genDiff($this->file1, $this->file2));
    }

    public function testGetFileData(): void
    {
        $expected1 = (object)[
            'host' => "hexlet.io",
            'timeout' => 50,
            'proxy' => "123.234.53.22",
            'follow' => false
        ];
        $expected2 = (object) [
            "timeout" => 20,
            "verbose" => true,
            "host" => "hexlet.io",
            "about" => "test"
        ];
        $this->assertEquals($expected1, getFileData($this->file1));
        $this->assertEquals($expected2, getFileData($this->file2));
    }
}