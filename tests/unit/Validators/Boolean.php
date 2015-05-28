<?php


class Volan_Validator_Boolean extends PHPUnit_Framework_TestCase
{

    public function testErrorOnValidation()
    {
        require dirname(dirname(__DIR__)).'/fixture/fixture20.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_IS_NOT_VALID, $expectedErrorCode);
    }
    
    public function testErrorOnValidationRequired()
    {
        require dirname(dirname(__DIR__)).'/fixture/fixture23.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_IS_NOT_VALID, $expectedErrorCode);
    }
    
    public function testSuccessValidation()
    {
        require dirname(dirname(__DIR__)).'/fixture/fixture21.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }
}