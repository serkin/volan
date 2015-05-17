<?php

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

class Volan_Multidimensional extends PHPUnit_Framework_TestCase
{
    public $logger;
    
    public function setUp()
    {
        $filename = __DIR__. '/log.txt';
        @unlink($filename);
        $this->logger = new Logger('name');
        $this->logger->pushHandler(new StreamHandler($filename));
    }

    public function testErrorOnMissingTypeField()
    {
        require __DIR__.'/fixture/fixture17.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_HAS_NO_FIELD_TYPE, $expectedErrorCode);
    }

    public function testErrorOnMissingRequiredField()
    {
        require __DIR__.'/fixture/fixture8.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_REQUIRED_FIELD_IS_EMPTY, $expectedErrorCode);
    }

    public function testErrorOnInvalidNode()
    {
        require __DIR__.'/fixture/fixture9.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_IS_NOT_VALID, $expectedErrorCode);
    }
    
    public function testErrorOnExcessiveKeys()
    {
        require __DIR__.'/fixture/fixture11.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_HAS_EXCESSIVE_KEYS, $expectedErrorCode);
    }

    public function testErrorOnExcessiveKeysInNestedArray()
    {
        require __DIR__.'/fixture/fixture13.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_HAS_EXCESSIVE_KEYS, $expectedErrorCode);
    }
    
    public function testErrorOnExcessiveKeysInNestedArrayWithTwoArrays()
    {
        require __DIR__.'/fixture/fixture15.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_HAS_EXCESSIVE_KEYS, $expectedErrorCode);
    }
    
    public function testSuccessValidationInNestedArrayWithTwoArrays()
    {
        require __DIR__.'/fixture/fixture14.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }
    
    public function testSuccessValidationInNestedArray()
    {
        require __DIR__.'/fixture/fixture12.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }

    public function testSuccessValidationInNestedArrayWithEmptySubArray()
    {
        require __DIR__.'/fixture/fixture16.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }

    public function testSuccessValidation()
    {
        require __DIR__.'/fixture/fixture10.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }
}
