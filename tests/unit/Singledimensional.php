<?php

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

class Volan_Singledimensional extends PHPUnit_Framework_TestCase
{
    public $logger;
        
    public function setUp()
    {
        $filename = dirname(__DIR__). '/log.txt';
        @unlink($filename);
        $this->logger = new Logger('name');
        $this->logger->pushHandler(new StreamHandler($filename));
    }
    public function testErrorOnMissingTypeField()
    {
        require dirname(__DIR__).'/fixture/fixture3.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_HAS_NO_FIELD_TYPE, $expectedErrorCode);
    }
    
    public function testErrorOnMissingRequiredField()
    {
        require dirname(__DIR__).'/fixture/fixture4.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_REQUIRED_FIELD_IS_EMPTY, $expectedErrorCode);
    }

    public function testErrorOnInvalidNode()
    {
        require dirname(__DIR__).'/fixture/fixture5.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_IS_NOT_VALID, $expectedErrorCode);
    }
    
    public function testErrorOnExtraKyes()
    {
        require dirname(__DIR__).'/fixture/fixture7.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_HAS_EXCESSIVE_KEYS, $expectedErrorCode);
    }
    public function testSuccessValidation()
    {
        require dirname(__DIR__).'/fixture/fixture6.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }
}
