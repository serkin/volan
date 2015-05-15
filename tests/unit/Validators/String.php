<?php


class String_Validator extends PHPUnit_Framework_TestCase
{
    
    public function testErrorOnInvalidData()
    {
        $schema = [
            'root' => [
                'name' => [
                    'type' => 'required_tring'
                    ]
                ]
            ];

            $arr = [
            'name' => 'I am a correct string'
            ];

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = explode(':', $validator->getError())[0];

        $this->assertEquals(2, $expectedErrorCode);
    }
    
    public function testErrorOnInvalidDataInDepth()
    {}
    
    public function testSuccessOnValidData()
    {}
    
    public function testSuccessOnValidDataInDepth()
    {}
    
}