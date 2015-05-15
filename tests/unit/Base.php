<?php


class Volan_Base extends PHPUnit_Framework_TestCase
{
    public function testErrorOnMissingRootNode()
    {
        require __DIR__.'/fixture/base/fixture1.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = explode(':', $validator->getError())[0];

        $this->assertEquals(\Volan\Volan::ERROR_SCHEMA_HAS_NO_ROOT_ELEMENT, $expectedErrorCode);
    }

    public function testErrorOnMissingValidatorClaa()
    {
        require __DIR__.'/fixture/base/fixture2.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = explode(':', $validator->getError())[0];

        $this->assertEquals(\Volan\Volan::ERROR_VALIDATOR_CLASS_NOT_FOUND, $expectedErrorCode);
    }
    
    }
