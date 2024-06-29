<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;
use function Differ\Parser\parseFile;

class GenDiffTest extends TestCase
{
    public string $file1 = 'tests/fixtures/filebigpath1.yml';
    public string $file2 = 'tests/fixtures/filebigpath2.yaml';
    public function testGenDiff(): void
    {
        $expected1 = file_get_contents('fixtures/simpleRes.txt', true);
        $expected2 = file_get_contents('fixtures/plainRes.txt', true);
        $expected3 = file_get_contents('fixtures/jsonRes.json', true);

        $this->assertEquals($expected1, genDiff('tests/fixtures/filebig1.json', 'tests/fixtures/filebig2.json'));
        $this->assertEquals($expected1, genDiff($this->file1, $this->file2, 'stylish'));
        $this->assertEquals($expected3, genDiff($this->file1, $this->file2, 'json'));
        $this->assertEquals($expected2, genDiff($this->file1, $this->file2, 'plain'));
    }

    public function testGetFileData(): void
    {
        $expected1 = (object) [
            "common" => (object) [
                "setting1" => "Value 1",
                "setting2" => 200,
                "setting3" => true,
                "setting6" => (object) [
                    "key" => "value",
                    "doge" => (object)[
                        "wow" => ""
                    ]
                ]
            ],
            "group1" => (object) [
                "baz" => "bas",
                "foo" => "bar",
                "nest" => (object) [
                    "key" => "value"
                ]
            ],
            "group2" => (object) [
                "abc" => 12345,
                "deep" => (object) [
                    "id" => 45
                ]
            ]
        ];

        $this->assertTrue($expected1 == parseFile('tests/fixtures/filebig1.json'));
        $this->assertTrue($expected1 == parseFile($this->file1));
    }
}
