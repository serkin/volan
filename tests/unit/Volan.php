<?php

use Volan\Volan as v;
use Volan\ValidatorResult;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Volan extends PHPUnit_Framework_TestCase
{

    public $fixtureFileName;

    public function setUp() {
        $this->fixtureFileName = dirname(__DIR__) . '/fixtures/fixture.php';
        require_once dirname(__DIR__) . '/fixtures/array_of_strings_validator.php';
        parent::setUp();
    }

    public function getSchemaFixture() {


        return [
            'root' => [
                'title' => [
                    '_type' => 'required_string'
                ],
                'characters' => [
                    '_type' => 'array_of_strings'
                ],
                'price' => [
                    '_type' => 'number'
                ],
                'tags' => [
                    '_type' => 'array'
                ],
                'instock' => [
                    '_type' => 'required_boolean'
                ],
                'reserved' => [
                    '_type' => 'boolean'
                ],
                'author' => [
                    '_type' => 'required_array',
                    'name' => [
                        '_type' => 'required_string'
                    ],
                ],
                'comments' => [
                    '_type' => 'nested_array',
                    'comment' => [
                        '_type' => 'required_string'
                    ],
                    'userid' => [
                        '_type' => 'required_number'
                    ],
                    'rating' => [
                        '_type' => 'number'
                    ]
                ]
            ]
        ];

    }


    public function getDataFixture() {

        return  [
            'title'     => 'The Idiot',
            'instock'   => true,
            'reserved'  => false,
            'price'     => 60,
            'characters' => ['Lev Nikolayevich Myshkin'],
            'author' => ['name' => 'Fyodor Dostoyevsky'],
            'tags'      => ['novel', 'russia'],
            'comments'  => [
                [
                    'comment'   => 'Good book',
                    'userid'    => 1,
                    'rating'    => 10
                ],
                [
                    'comment'   => 'I love it',
                    'userid'    => 2,
                    'rating'    => 10
                ],
            ]
        ];
    }

    public function testSuccessfulValidation()
    {

        $validator = new v($this->getSchemaFixture());
        $result = $validator->validate($this->getDataFixture());

        $this->assertTrue($result->isValid());
    }

    public function testErrorOnMissingRootNode()
    {

        $schema = ['roooot' => []];

        $result = (new v($schema))->validate($this->getDataFixture());

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_SCHEMA_HAS_NO_ROOT_ELEMENT, $result->getErrorCode());
        $this->assertEquals('root', $result->getErrorNode());

    }

    public function testErrorOnMissingValidatorClaa()
    {

        $schema = $this->getSchemaFixture();
        $schema['root']['price'] = ['_type' => 'numbers'];  // Let's corrupt data. There is no validator NUMBERS

        $result = (new v($schema))->validate($this->getDataFixture());

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_VALIDATOR_CLASS_NOT_FOUND, $result->getErrorCode());
        $this->assertEquals('root.price', $result->getErrorNode());

    }

    public function testErrorOnMissingTypeField()
    {

        $schema = $this->getSchemaFixture();
        unset($schema['root']['price']['_type']);

        $result = (new v($schema))->validate($this->getDataFixture());

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_NODE_HAS_NO_FIELD_TYPE, $result->getErrorCode());
        $this->assertEquals('root.price', $result->getErrorNode());
    }

    public function testErrorOnMissingTypeFieldInDepth()
    {

        $schema = $this->getSchemaFixture();
        unset($schema['root']['comments']['comment']['_type']);


        $result = (new v($schema))->validate($this->getDataFixture());

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_NODE_HAS_NO_FIELD_TYPE, $result->getErrorCode());
        $this->assertEquals('comments.comment', $result->getErrorNode());
    }



    public function testErrorOnInvalidNode()
    {

        $arr = $this->getDataFixture();
        $arr['instock'] = 'instock'; // Let's corrupt data. Instock has to be BOOLEAN

        $result = (new v($this->getSchemaFixture()))->validate($arr);

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_NODE_IS_NOT_VALID, $result->getErrorCode());
        $this->assertEquals('root.instock', $result->getErrorNode());
    }

    public function testErrorOnInvalidNodeInDepth()
    {

        $arr = $this->getDataFixture();
        $arr['comments'][0]['userid'] = 'John'; // Let's corrupt data. Userid has to be NUMBER

        $result = (new v($this->getSchemaFixture()))->validate($arr);

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_NODE_IS_NOT_VALID, $result->getErrorCode());
        $this->assertEquals('comments.userid', $result->getErrorNode());
    }

    public function testErrorInNestedNode()
    {

        $arr = $this->getDataFixture();
        $arr['comments'] = ['comment' => 'comment']; // Let's corrupt data. Comments has to be nested

        $result = (new v($this->getSchemaFixture()))->validate($arr);

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_NESTED_ELEMENT_NOT_VALID, $result->getErrorCode());
        $this->assertEquals('root.comments', $result->getErrorNode());
    }

    public function testErrorOnMissingRequiredField()
    {

        $arr = $this->getDataFixture();
        unset($arr['title']); // Let's corrupt data. Title is REQUIRED

        $result = (new v($this->getSchemaFixture()))->validate($arr);

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_REQUIRED_FIELD_IS_EMPTY, $result->getErrorCode());
        $this->assertEquals('root.title', $result->getErrorNode());
    }

    public function testErrorOnMissingRequiredFieldInDepth()
    {

        $arr = $this->getDataFixture();
        unset($arr['comments'][0]['comment']); // Let's corrupt data. Title is REQUIRED

        $result = (new v($this->getSchemaFixture()))->validate($arr);

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_REQUIRED_FIELD_IS_EMPTY, $result->getErrorCode());
        $this->assertEquals('comments.comment', $result->getErrorNode());
    }

    public function testErrorOnExtraKeys()
    {

        $arr = $this->getDataFixture();
        $arr['extrakey'] = []; // Let's corrupt data. Add excessive keys

        $result = (new v($this->getSchemaFixture()))->validate($arr);

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_NODE_HAS_EXCESSIVE_KEYS, $result->getErrorCode());
        $this->assertEquals('root', $result->getErrorNode());
    }

    public function testErrorOnExtraKeysInDepth()
    {

        $arr = $this->getDataFixture();
        $arr['comments'][0]['extrakey'] = []; // Let's corrupt data. Add excessive keys

        $result = (new v($this->getSchemaFixture()))->validate($arr);

        $this->assertFalse($result->isValid());
        $this->assertEquals(ValidatorResult::ERROR_NODE_HAS_EXCESSIVE_KEYS, $result->getErrorCode());
        $this->assertEquals('root.comments', $result->getErrorNode());
    }

    public function testSettingRequiredMode()
    {

        $arr = $this->getDataFixture();
        unset($arr['author']);

        $validator = new v($this->getSchemaFixture());
        $validator->setRequiredMode(false);

        $result = $validator->validate($arr);

        $this->assertTrue($result->isValid());
    }

    public function testSettingRequiredModeInDepth()
    {

        $arr = $this->getDataFixture();
        unset($arr['comments'][0]['userid']);

        $validator = new v($this->getSchemaFixture());
        $validator->setRequiredMode(false);

        $result = $validator->validate($arr);

        $this->assertTrue($result->isValid());
    }

    public function testStrictMode()
    {

        $arr = $this->getDataFixture();
        $arr['extrakey'] = []; // Let's corrupt data. Add excessive keys

        $validator = new v($this->getSchemaFixture(), false);
        $result = $validator->validate($arr);

        $this->assertTrue($result->isValid());
    }

    public function testStrictModeInDepth()
    {

        $arr = $this->getDataFixture();
        $arr['comments'][0]['extrakey'] = []; // Let's corrupt data. Add excessive keys


        $validator = new v($this->getSchemaFixture(), false);
        $result = $validator->validate($arr);

        $this->assertTrue($result->isValid());
    }

    public function testLogger()
    {

        $arr = $this->getDataFixture();
        $arr['comments'][0]['extrakey'] = []; // Let's corrupt data. Add excessive keys

        $validator = new v($this->getSchemaFixture());
        $result = $validator->validate($arr);

        $this->assertNotFalse(strpos($result->getLog(), 'element has excessive keys'));


    }

    public function testGetErrorDescriptionFromValidator()
    {

        $arr = $this->getDataFixture();

        $arr['characters'] = 'Sctring'; // Let's corrupt data. Value has to be array of string

        $result = (new v($this->getSchemaFixture()))->validate($arr);

        $this->assertFalse($result->isValid());
        $this->assertEquals(1, preg_match('/Value supposed to be an array/', $result->getErrorMessage()));

    }

}
