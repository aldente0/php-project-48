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
        $expected = file_get_contents('fixtures/simpleRes.txt', true);

        $this->assertEquals($expected, genDiff('tests/fixtures/filebig1.json', 'tests/fixtures/filebig2.json'));
        $this->assertEquals($expected, genDiff($this->file1, $this->file2, 'stylish'));
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