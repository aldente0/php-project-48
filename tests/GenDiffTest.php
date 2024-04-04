<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;
use function Differ\Differ\getFileData;

class GenDiffTest extends TestCase
{
    public $file1 = 'filepath1.yml';
    public $file2 = 'filepath2.yaml';
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

    public function testGetFileData(): void
    {
        $expected1 = [
            'host' => "hexlet.io",
            'timeout' => 50,
            'proxy' => "123.234.53.22",
            'follow' => false
        ];
        $expected2 = [
            "timeout" => 20,
            "verbose" => true,
            "host" => "hexlet.io",
            "about" => "test"
        ];
        $this->assertEquals($expected1, getFileData($this->file1));
        $this->assertEquals($expected2, getFileData($this->file2));
    }
}