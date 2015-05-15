<?php


class Volan_Singledimensional extends PHPUnit_Framework_TestCase
{
    
    public function testErrorOnMissingTypeField()
    {
        require __DIR__.'/fixture/singledimensional/fixture3.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = explode(':', $validator->getError())[0];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_HAS_NO_FIELD_TYPE, $expectedErrorCode);
    }
    
    public function testErrorOnMissingRequiredField()
    {
        require __DIR__.'/fixture/singledimensional/fixture4.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);

        $expectedErrorCode = explode(':', $validator->getError())[0];

        $this->assertEquals(\Volan\Volan::ERROR_REQUIRED_FIELD_IS_EMPTY, $expectedErrorCode);
    }

    public function testErrorOnInvalidNode()
    {
        require __DIR__.'/fixture/singledimensional/fixture5.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $expectedErrorCode = explode(':', $validator->getError())[0];

        $this->assertEquals(\Volan\Volan::ERROR_NODE_IS_NOT_VALID, $expectedErrorCode);
    }
    
    public function testSuccessValidation()
    {
        require __DIR__.'/fixture/singledimensional/fixture6.php';

        $validator = new \Volan\Volan($schema);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }
}
