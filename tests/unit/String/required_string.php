<?php

use Symfony\Component\Yaml\Parser;

class required_string extends PHPUnit_Framework_TestCase
{
    public $schema;


    public function setUp()
    {
        $yaml = new Parser();
        $this->schema = $yaml->parse(file_get_contents($GLOBALS['fixture_path'] . '/schema/string/required_string.yml'));
    }

    public function additionProvider()
    {
        return [
            ['title' => null],
            ['title' => []],
            ['title' => ''],
            ['title' => [1,2,3]]
        ];
    }

    /**
     * @dataProvider additionProvider
     */
    public function testInvalidData($arr)
    {

        $validator = new \Volan\Volan($this->schema);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }
}