<?php

namespace Volan;

use \Psr\Log\LoggerInterface;

class Volan
{
    /**
     * @var string
     */
    private $error = '';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger = null;

    /**
     * @var bool
     */
    private $strictMode;

    /**
     * @var string
     */
    private $currentNode = '';
    /**
     * @var array
     */
    private $schema = [];

    const ERROR_NODE_HAS_EXCESSIVE_KEYS     = 1;
    const ERROR_NODE_HAS_NO_FIELD_TYPE      = 2;
    const ERROR_SCHEMA_HAS_NO_ROOT_ELEMENT  = 3;
    const ERROR_VALIDATOR_CLASS_NOT_FOUND   = 4;
    const ERROR_REQUIRED_FIELD_IS_EMPTY     = 5;
    const ERROR_NODE_IS_NOT_VALID           = 6;
    const ERROR_NESTED_ELEMENT_NOT_VALID    = 7;

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param array $schema
     * @param bool  $strictMode
     */
    public function __construct($schema, $strictMode = true)
    {
        $this->schema       = $schema;
        $this->strictMode   = $strictMode;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function log($message)
    {
        if (!is_null($this->logger)):
            $this->logger->info($message);
        endif;
    }

    /**
     * @param string $message
     * @param int    $code
     */
    private function setError($message, $code)
    {
        $this->error = $code.': '.$message;
    }

    /**
     * @param array $arr
     *
     * @return bool
     */
    public function validate($arr)
    {
        $returnValue = true;

        try {
            if (empty($this->schema['root'])):
                throw new \Exception('Sorry no root element in schema', self::ERROR_SCHEMA_HAS_NO_ROOT_ELEMENT);
            endif;

            $this->validateNode('root', new CustomArrayObject($this->schema), $arr);
        } catch (\Exception $exc) {
            $this->setError($exc->getMessage(), $exc->getCode());
            $this->log($exc->getMessage());

            $returnValue = false;
        }

        return $returnValue;
    }

    /**
     * @param string                   $node
     * @param \Volan\CustomArrayObject $schema
     * @param mixed                    $element
     *
     * @throws \Exception
     */
    private function validateNode($node, CustomArrayObject $schema, $element = [])
    {
        $nodeSchema = new CustomArrayObject($schema[$node]);

        foreach ($nodeSchema->getArrayKeys() as $key):

            $this->currentNode = $node.'.'.$key;
            $this->log("We in element: {$this->currentNode}");

            $nodeData = isset($element[$key]) ? $element[$key] : null;

            $this->validatingTypeField($nodeSchema[$key]);

            $validator = $this->getValidatorClass($nodeSchema[$key]);

            $this->validateRequiredField($validator, $nodeData);

            if ($validator->isRequired() === false  && empty($nodeData)):
                $this->log("Element: {$this->currentNode} has empty nonrequired data. We skip other check");
                continue;
            endif;

            $this->validatingExcessiveKeys($validator, new CustomArrayObject($nodeSchema[$key]), $nodeData);

            $this->validateField($validator, new CustomArrayObject($nodeSchema[$key]), $nodeData);

            $this->validateNestedField($validator, $nodeData);

            if ($validator->isNested()):

                $this->log("Element: {$this->currentNode} is has children");

                foreach ($nodeData as $record):
                    $this->validateNode($key, $nodeSchema, $record);
                endforeach;

            else:
                $this->validateNode($key, $nodeSchema, $nodeData);
            endif;

            $this->log("Element: {$this->currentNode} finished checking successfully.");

        endforeach;
    }

    /**
     * @param \Volan\CustomArrayObject $nodeSchema
     * @param array                    $nodeData
     *
     * @return bool
     */
    private function isChildElementHasStrictKeys(\Volan\CustomArrayObject $nodeSchema, $nodeData)
    {
        $returnValue = false;

        if (!empty($nodeData) && is_array($nodeData)):
            $schemaKeys     = $nodeSchema->getArrayKeys();
            $dataKeys       = array_keys($nodeData);
            $returnValue    = (bool) array_diff($dataKeys, $schemaKeys);
        endif;

        return $returnValue;
    }

    /**
     * @param \Volan\Validator\AbstractValidator $validator
     * @param \Volan\CustomArrayObject           $schema
     * @param mixed                              $nodeData
     *
     * @throws \Exception
     */
    private function validatingExcessiveKeys(\Volan\Validator\AbstractValidator $validator, CustomArrayObject $schema, $nodeData = null)
    {
        if (!$validator->isNested() && $this->strictMode && $this->isChildElementHasStrictKeys($schema, $nodeData)):
            throw new \Exception("Sorry {$this->currentNode} element has excessive keys", self::ERROR_NODE_HAS_EXCESSIVE_KEYS);
        endif;

        if ($validator->isNested() && $this->strictMode):
            foreach ($nodeData as $record):
                if ($this->isChildElementHasStrictKeys($schema, $record)):
                    throw new \Exception("Sorry children of element: {$this->currentNode} has excessive keys", self::ERROR_NODE_HAS_EXCESSIVE_KEYS);
                endif;
            endforeach;
        endif;
    }

    /**
     * @param array $node
     *
     * @throws \Exception
     */
    private function validatingTypeField($node)
    {
        if (empty($node['_type'])):
            throw new \Exception("Sorry element: {$this->currentNode} has no compulsory field: _type", self::ERROR_NODE_HAS_NO_FIELD_TYPE);
        endif;

        $this->log("Element: {$this->currentNode} has field: _type");
    }

    /**
     * @param \Volan\Validator\AbstractValidator $validator
     * @param mixed                              $nodeData
     *
     * @throws \Exception
     */
    private function validateRequiredField(\Volan\Validator\AbstractValidator $validator, $nodeData = null)
    {
        if ($validator->isRequired() && empty($nodeData)):
            throw new \Exception("Sorry {$this->currentNode} element has flag *required*", self::ERROR_REQUIRED_FIELD_IS_EMPTY);
        endif;

        $this->log('*required* check passed');
    }

    /**
     * @param array $node
     *
     * @return \Volan\Validator\AbstractValidator
     *
     * @throws \Exception
     */
    private function getValidatorClass($node)
    {
        $nodeTypeClassString = '\Volan\Validator\\'.$node['_type'].'_validator';

        if (!class_exists($nodeTypeClassString)):
            throw new \Exception("Sorry validator class {$nodeTypeClassString} not found", self::ERROR_VALIDATOR_CLASS_NOT_FOUND);
        endif;

        $this->log("validatot class $nodeTypeClassString exists");

        return new $nodeTypeClassString();
    }

    /**
     * @param \Volan\Validator\AbstractValidator $validator
     * @param \Volan\CustomArrayObject           $schema
     * @param mixed                              $nodeData
     *
     * @throws \Exception
     */
    private function validateField(\Volan\Validator\AbstractValidator $validator, CustomArrayObject $schema, $nodeData = null)
    {
        if ($validator->isValid($schema, $nodeData) === false):
            throw new \Exception("Sorry {$this->currentNode} element has invalid associated data", self::ERROR_NODE_IS_NOT_VALID);
        endif;
    }

    /**
     * @param \Volan\Validator\AbstractValidator $validator
     * @param mixed                              $nodeData
     *
     * @throws \Exception
     */
    private function validateNestedField(\Volan\Validator\AbstractValidator $validator, $nodeData)
    {
        if ($validator->isNested() && !is_array($nodeData[0])):
            throw new \Exception("{$this->currentNode} element supposed to be nested but it is not", self::ERROR_NESTED_ELEMENT_NOT_VALID);
        endif;
    }
}
