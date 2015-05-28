<?php


class Volan_Base extends PHPUnit_Framework_TestCase
{
    public function testErrorOnMissingRootNode()
    {
        require dirname(__DIR__).'/fixture/fixture1.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_SCHEMA_HAS_NO_ROOT_ELEMENT, $expectedErrorCode);
    }

    public function testErrorOnMissingValidatorClaa()
    {
        require dirname(__DIR__).'/fixture/fixture2.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = $validator->getErrorInfo()['code'];

        $this->assertEquals(\Volan\Volan::ERROR_VALIDATOR_CLASS_NOT_FOUND, $expectedErrorCode);
    }
    
    }
