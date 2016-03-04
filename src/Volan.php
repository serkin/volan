<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan;

use Volan\Validator\AbstractValidator;
use Exception;
use Volan\Traits\LoggerTrait;
use Volan\Traits\ErrorHandlerTrait;


class Volan
{

    use ErrorHandlerTrait;
    use LoggerTrait;

    /**
     * If set to false all required fields set can be empty
     *
     * @var bool
     */
    private $requiredMode = true;

    /**
     * If set to false allows excessive keys in array. Default is true
     *
     * @var bool
     */
    private $strictMode;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    private $currentNode = '';
    /**
     * @var array
     */
    private $schema = [];


    /**
     * @param array $schema
     * @param bool  $strictMode
     */
    public function __construct($schema, $strictMode = true)
    {
        $this->schema       = $schema;
        $this->strictMode   = $strictMode;

        $log = new InMemoryLogger();
        $this->setLogger($log);
    }

    /**
     * Sets required mode
     *
     * @param bool $mode
     */
    public function setRequiredMode($mode = true)
    {
        $this->requiredMode = $mode;
    }

    /**
     * Sets custom params
     *
     * @param array $arr
     * @return void
     *
     */
    public function setParams($arr = []) {
        $this->params = $arr;
    }


    /**
     * @param array $arr
     *
     * @return ValidatorResult
     */
    public function validate($arr)
    {
        $returnValue = new ValidatorResult();
        $this->currentNode = 'root';

        try {

            $this->validateSchemaBeginsWithRootKey(new CustomArrayObject($this->schema));

            if ($this->strictMode)
                $this->validateExcessiveKeysAbsent(new CustomArrayObject($this->schema['root']), $arr);

            $this->validateNode('root', new CustomArrayObject($this->schema), $arr);

        } catch (Exception $exc) {

            $this->getLogger()->error($exc->getMessage());

            $returnValue->setError($exc->getCode(), $this->getCurrentNode(), $exc->getMessage());
            $returnValue->setLog($this->getLogger()->getLog());
        }

        return $returnValue;
    }

    public function getCurrentNode()
    {
        return $this->currentNode;
    }

    /**
     * @param string            $node
     * @param CustomArrayObject $schema
     * @param mixed             $element
     *
     * @throws Exception
     */
    private function validateNode($node, CustomArrayObject $schema, $element = [])
    {
        $nodeSchema = new CustomArrayObject($schema[$node]);

        foreach ($nodeSchema->getArrayKeys() as $key) {

            $this->currentNode = $node . '.' . $key;
            $this->getLogger()->info("We are in element: {$this->currentNode}");

            $nodeData = isset($element[$key]) ? $element[$key] : null;


            $this->validateTypeFieldIsPresent($nodeSchema[$key]);

            $validator = $this->getClassValidator($nodeSchema[$key]);
            $validator->setParams($this->params);

            $isRequired = $this->requiredMode ? $validator->isRequired() : false;

            if ($isRequired === false && empty($nodeData)) {
                $this->getLogger()->info("Element: {$this->currentNode} has empty non-required data. We skip other check");
                continue;
            }

            $this->validateRequiredFieldIsPresent($nodeData);

            $this->validateExcessiveKeys($validator, new CustomArrayObject($nodeSchema[$key]), $nodeData);

            $this->validateNodeValue($validator, $nodeData);

            $this->validateNesting($validator, $nodeData);

            if ($validator->isNested()) {

                $this->getLogger()->info("Element: {$this->currentNode} has children");

                foreach ($nodeData as $record) {
                    $this->validateNode($key, $nodeSchema, $record);
                }

            } else {
                $this->validateNode($key, $nodeSchema, $nodeData);
            }

            $this->getLogger()->info("Element: {$this->currentNode} finished checking successfully.");

        }
    }

    /**
     * @param CustomArrayObject $nodeSchema
     * @param array             $nodeData
     *
     * @return bool
     */
    private function isChildElementHasStrictKeys(CustomArrayObject $nodeSchema, $nodeData)
    {
        $returnValue = false;

        if (!empty($nodeData) && is_array($nodeData)) {
            $schemaKeys = $nodeSchema->getArrayKeys();
            $dataKeys = count(array_filter(array_keys($nodeData), 'is_string')) ? array_keys($nodeData) : [];
            $returnValue = (bool)array_diff($dataKeys, $schemaKeys);
        }

        return $returnValue;
    }

    /**
     * @param AbstractValidator $validator
     * @param CustomArrayObject $schema
     * @param mixed             $nodeData
     *
     */
    private function validateExcessiveKeys(AbstractValidator $validator, CustomArrayObject $schema, $nodeData = null)
    {
        if ($this->strictMode === false) {
            return;
        }

        if (!$validator->isNested()) {
            $this->validateExcessiveKeysAbsent($schema, $nodeData);
        } else {
            foreach ($nodeData as $record) {
                $this->validateExcessiveKeysAbsent($schema, $record);
            }
        }
    }

    /**
     * @param CustomArrayObject           $schema
     * @param mixed                              $nodeData
     *
     * @throws Exception
     */
    private function validateExcessiveKeysAbsent($schema, $nodeData)
    {
        if ($this->isChildElementHasStrictKeys($schema, $nodeData)) {
            throw new Exception("{$this->currentNode} element has excessive keys", ValidatorResult::ERROR_NODE_HAS_EXCESSIVE_KEYS);
        }
    }

    /**
     * @param array $node
     *
     * @throws Exception
     */
    private function validateTypeFieldIsPresent($node)
    {
        if (empty($node['_type'])) {
            throw new Exception("Element: {$this->currentNode} has no compulsory field: _type", ValidatorResult::ERROR_NODE_HAS_NO_FIELD_TYPE);
        }

        $this->getLogger()->info("Element: {$this->currentNode} has field: _type");
    }

    /**

     * @param mixed $nodeData
     *
     * @throws Exception
     */
    private function validateRequiredFieldIsPresent($nodeData = null)
    {

        if (empty($nodeData)) {
            throw new Exception("{$this->currentNode} element has flag *required*", ValidatorResult::ERROR_REQUIRED_FIELD_IS_EMPTY);
        }

        $this->getLogger()->info('*required* check passed');
    }

    /**
     * @param array $node
     *
     * @return AbstractValidator
     *
     * @throws Exception
     */
    private function getClassValidator($node)
    {

        $classStringName = $node['_type'].'_validator';
        $classStringNamespace = '\Volan\Validator\\';

        $classNames = [];
        $classNames[] = $classStringNamespace.$classStringName;
        $classNames[] = $classStringNamespace.$this->getPSRCompatibleClassName($classStringName);

        if (class_exists($classNames[0])) {
            $validatorClass = new $classNames[0]();
        } elseif (class_exists($classNames[1])) {
            $validatorClass = new $classNames[1]();
        } else {
            throw new Exception("Class validator {$classNames[0]}/{$classNames[1]} not found", ValidatorResult::ERROR_VALIDATOR_CLASS_NOT_FOUND);
        }

        $this->getLogger()->info("Class validator ".get_class($validatorClass)." exists");

        return $validatorClass;

    }

    /**
     * Validate that schema begins with root element
     *
     * @param CustomArrayObject $schema
     *
     * @throws Exception
     *
     * @return void
     */
    private function validateSchemaBeginsWithRootKey(CustomArrayObject $schema)
    {
        if (empty($schema['root'])) {
            throw new Exception('No root element in schema', ValidatorResult::ERROR_SCHEMA_HAS_NO_ROOT_ELEMENT);
        }
    }
    /*
     * Converts string constisting _ to PSR compatible class name
     *
     * @param string
     *
     * @return string
     */
    private function getPSRCompatibleClassName($string)
    {
        $className = '';
        $arr = explode('_', $string);

        foreach ($arr as $key => $value) {
            $className .= ucfirst(strtolower($value));
        }

        return $className;
    }

    /**
     * @param AbstractValidator $validator
     * @param mixed                              $nodeData
     *
     * @throws Exception
     */
    private function validateNodeValue(AbstractValidator $validator, $nodeData = null)
    {
        if ($validator->isValid($nodeData) === false) {

            $error = $this->currentNode . " element has invalid associated data.";
            $error .= !is_null($validator->getErrorDescription())
                ? $validator->getErrorDescription()
                : '';

            throw new Exception($error, ValidatorResult::ERROR_NODE_IS_NOT_VALID);
        }
    }

    /**
     * @param AbstractValidator $validator
     * @param mixed                              $nodeData
     *
     * @throws Exception
     */
    private function validateNesting(AbstractValidator $validator, $nodeData)
    {
        if ($validator->isNested() && (!isset($nodeData[0]) || !is_array($nodeData[0]))) {
            throw new Exception("{$this->currentNode} element supposed to be nested but it is not", ValidatorResult::ERROR_NESTED_ELEMENT_NOT_VALID);
        }
    }
}
