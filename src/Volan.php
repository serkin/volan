<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */
        
namespace Volan;

use Psr\Log\LoggerInterface;

class Volan
{
    /**
     * @var array
     */
    private $error;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    
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
     * @return array
     */
    public function getErrorInfo()
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

        $log = new DummyLogger('volan');
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

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Gets current logger
     * 
     * @return \Psr\Log\LoggerInterface
     */
    private function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param string $message
     * @param int    $code
     */
    private function setError($message, $code)
    {
        $this->error = [
            'code'  => $code,
            'error' => $message,
            'node'  => $this->currentNode
                ];
    }

    /**
     * @param array $arr
     *
     * @return bool
     */
    public function validate($arr)
    {
        $returnValue = true;
        $this->currentNode = 'root';

        try {

            if (empty($this->schema['root'])):
                throw new \Exception('No root element in schema', self::ERROR_SCHEMA_HAS_NO_ROOT_ELEMENT);
            endif;
            
            if ($this->strictMode && $this->isChildElementHasStrictKeys(new CustomArrayObject($this->schema['root']), $arr)):
                throw new \Exception("root element has excessive keys", self::ERROR_NODE_HAS_EXCESSIVE_KEYS);
            endif;

            $this->validateNode('root', new CustomArrayObject($this->schema), $arr);
        } catch (\Exception $exc) {
            $this->setError($exc->getMessage(), $exc->getCode());
            $this->getLogger()->warning($exc->getMessage());

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
            $this->getLogger()->info("We are in element: {$this->currentNode}");

            $nodeData = isset($element[$key]) ? $element[$key] : null;

            $this->validatingTypeField($nodeSchema[$key]);

            $validator = $this->getClassValidator($nodeSchema[$key]);

            $this->validateRequiredField($validator, $nodeData);
            
            $isRequired = $this->requiredMode ? $validator->isRequired() : false;

            if ($isRequired === false && empty($nodeData)):
                $this->getLogger()->info("Element: {$this->currentNode} has empty nonrequired data. We skip other check");
                continue;
            endif;

            $this->validatingExcessiveKeys($validator, new CustomArrayObject($nodeSchema[$key]), $nodeData);

            $this->validateField($validator, $nodeData);

            $this->validateNestedField($validator, $nodeData);

            if ($validator->isNested()):

                $this->getLogger()->info("Element: {$this->currentNode} is has children");

                foreach ($nodeData as $record):
                    $this->validateNode($key, $nodeSchema, $record);
                endforeach;

            else:
                $this->validateNode($key, $nodeSchema, $nodeData);
            endif;

            $this->getLogger()->info("Element: {$this->currentNode} finished checking successfully.");

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
            $dataKeys       = count(array_filter(array_keys($nodeData), 'is_string')) ? array_keys($nodeData) : [];
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
        if ($this->strictMode && !$validator->isNested() && $this->isChildElementHasStrictKeys($schema, $nodeData)):
            throw new \Exception("{$this->currentNode} element has excessive keys", self::ERROR_NODE_HAS_EXCESSIVE_KEYS);
        endif;

        if ($this->strictMode && $validator->isNested()):
            foreach ($nodeData as $record):
                if ($this->isChildElementHasStrictKeys($schema, $record)):
                    throw new \Exception("Children of element: {$this->currentNode} has excessive keys", self::ERROR_NODE_HAS_EXCESSIVE_KEYS);
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
            throw new \Exception("Element: {$this->currentNode} has no compulsory field: _type", self::ERROR_NODE_HAS_NO_FIELD_TYPE);
        endif;

        $this->getLogger()->info("Element: {$this->currentNode} has field: _type");
    }

    /**
     * @param \Volan\Validator\AbstractValidator $validator
     * @param mixed                              $nodeData
     *
     * @throws \Exception
     */
    private function validateRequiredField(\Volan\Validator\AbstractValidator $validator, $nodeData = null)
    {

        $isRequired = $this->requiredMode ? $validator->isRequired() : false;

        if ($isRequired && empty($nodeData)):
            throw new \Exception("{$this->currentNode} element has flag *required*", self::ERROR_REQUIRED_FIELD_IS_EMPTY);
        endif;

        $this->getLogger()->info('*required* check passed');
    }

    /**
     * @param array $node
     *
     * @return \Volan\Validator\AbstractValidator
     *
     * @throws \Exception
     */
    private function getClassValidator($node)
    {

        $classStringName = $node['_type'].'_validator';
        $classStringNamespace = '\Volan\Validator\\';

        $classNames = [];
        $classNames[] = $classStringNamespace . $classStringName;
        $classNames[] = $classStringNamespace . $this->getPSRCompatibleClassName($classStringName);

        $validatorClass = null;

        foreach ($classNames as $className):
            if(class_exists($className)):
                $validatorClass = new $className();
                $this->getLogger()->info("Class validator $className exists");
            endif;
        endforeach;


        if (is_null($validatorClass)):
            throw new \Exception("Class validator {$classNames[0]}/{$classNames[1]} not found", self::ERROR_VALIDATOR_CLASS_NOT_FOUND);
        endif;

        return $validatorClass;

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

        foreach ($arr as $key => $value):
            $className .= ucfirst(strtolower($value));
        endforeach;
        
        return $className;
    }

    /**
     * @param \Volan\Validator\AbstractValidator $validator
     * @param mixed                              $nodeData
     *
     * @throws \Exception
     */
    private function validateField(\Volan\Validator\AbstractValidator $validator, $nodeData = null)
    {
        if ($validator->isValid($nodeData) === false):
            throw new \Exception("{$this->currentNode} element has invalid associated data", self::ERROR_NODE_IS_NOT_VALID);
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
