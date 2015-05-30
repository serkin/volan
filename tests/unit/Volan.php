<?php

use Volan\Volan as v;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Volan extends PHPUnit_Framework_TestCase
{

    public $fixtureFileName;

    public function setUp() {
        $this->fixtureFileName = dirname(__DIR__) . '/fixture.php';
        parent::setUp();
    }
    
    public function testSuccessfulValidation()
    {
        require $this->fixtureFileName;

        $validator = new v($schema);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }

    public function testErrorOnMissingRootNode()
    {
        require $this->fixtureFileName;
        
        $schema = ['roooot' => []];

        $validator = new v($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $this->assertEquals(v::ERROR_SCHEMA_HAS_NO_ROOT_ELEMENT, $validator->getErrorInfo()['code']);
        $this->assertEquals('root', $validator->getErrorInfo()['node']);
        
    }

    public function testErrorOnMissingValidatorClaa()
    {

        require $this->fixtureFileName;

        $schema['root']['price'] = ['_type' => 'numbers'];  // Let's corrupt data. There is no validator NUMBERS

        $validator = new v($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $this->assertEquals(v::ERROR_VALIDATOR_CLASS_NOT_FOUND, $validator->getErrorInfo()['code']);
        $this->assertEquals('root.price', $validator->getErrorInfo()['node']);

    }
    
    public function testErrorOnMissingTypeField()
    {
        require $this->fixtureFileName;

        unset($schema['root']['price']['_type']);

        $validator = new v($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $this->assertEquals(v::ERROR_NODE_HAS_NO_FIELD_TYPE, $validator->getErrorInfo()['code']);
        $this->assertEquals('root.price', $validator->getErrorInfo()['node']);
    }

    public function testErrorOnMissingTypeFieldInDepth()
    {
        require $this->fixtureFileName;

        unset($schema['root']['comments']['comment']['_type']);

        $validator = new v($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $this->assertEquals(v::ERROR_NODE_HAS_NO_FIELD_TYPE, $validator->getErrorInfo()['code']);
        $this->assertEquals('comments.comment', $validator->getErrorInfo()['node']);
    }
    
    
    
    public function testErrorOnInvalidNode()
    {
        require $this->fixtureFileName;

        $arr['instock'] = 'instock'; // Let's corrupt data. Instock has to be BOOLEAN

        $validator = new v($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $this->assertEquals(v::ERROR_NODE_IS_NOT_VALID, $validator->getErrorInfo()['code']);
        $this->assertEquals('root.instock', $validator->getErrorInfo()['node']);
    }
    
    public function testErrorOnInvalidNodeInDepth()
    {
        require $this->fixtureFileName;

        $arr['comments'][0]['userid'] = 'John'; // Let's corrupt data. Userid has to be NUMBER

        $validator = new v($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $this->assertEquals(v::ERROR_NODE_IS_NOT_VALID, $validator->getErrorInfo()['code']);
        $this->assertEquals('comments.userid', $validator->getErrorInfo()['node']);
    }
    
    public function testErrorOnExtraKeys()
    {
        require $this->fixtureFileName;

        $arr['extrakey'] = []; // Let's corrupt data. Add excessive keys

        $validator = new v($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $this->assertEquals(v::ERROR_NODE_HAS_EXCESSIVE_KEYS, $validator->getErrorInfo()['code']);
        $this->assertEquals('root', $validator->getErrorInfo()['node']);
    }

    public function testErrorOnExtraKeysInDepth()
    {
        require $this->fixtureFileName;

        $arr['comments'][0]['extrakey'] = []; // Let's corrupt data. Add excessive keys

        $validator = new v($schema);
        $result = $validator->validate($arr);

        $this->assertFalse($result);
        $this->assertEquals(v::ERROR_NODE_HAS_EXCESSIVE_KEYS, $validator->getErrorInfo()['code']);
        $this->assertEquals('root.comments', $validator->getErrorInfo()['node']);
    }

    public function testSettingRequiredMode()
    {
        require $this->fixtureFileName;

        unset($arr['author']);

        $validator = new v($schema);
        $validator->setRequiredMode(false);

        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }
    
    public function testSettingRequiredModeInDepth()
    {
        require $this->fixtureFileName;

        unset($arr['comments'][0]['userid']);

        $validator = new v($schema);
        $validator->setRequiredMode(false);

        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }

    public function testStrictMode()
    {
        require $this->fixtureFileName;

        $arr['extrakey'] = []; // Let's corrupt data. Add excessive keys
                
        $validator = new v($schema, false);
        $result = $validator->validate($arr);

        $this->assertTrue($result);
    }

    public function testStrictModeInDepth()
    {
        require $this->fixtureFileName;

        $arr['comments'][0]['extrakey'] = []; // Let's corrupt data. Add excessive keys

        
        $validator = new v($schema, false);
        $result = $validator->validate($arr);
        
        $this->assertTrue($result);
    }

    public function testSettingLogger()
    {

        require $this->fixtureFileName;

        $filename =  tempnam(sys_get_temp_dir(), 'log');

        $log = new Logger('name');
        $log->pushHandler(new StreamHandler($filename));

        $validator = new v($schema);
        $validator->setLogger($log);
        $validator->validate($arr);
        
        $this->assertTrue(filesize($filename) > 0);
        
        @unlink($filename);
    }

}