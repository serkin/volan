<?php

namespace Volan;

class ValidatorResult
{

    const ERROR_NODE_HAS_EXCESSIVE_KEYS     = 1;
    const ERROR_NODE_HAS_NO_FIELD_TYPE      = 2;
    const ERROR_SCHEMA_HAS_NO_ROOT_ELEMENT  = 3;
    const ERROR_VALIDATOR_CLASS_NOT_FOUND   = 4;
    const ERROR_REQUIRED_FIELD_IS_EMPTY     = 5;
    const ERROR_NODE_IS_NOT_VALID           = 6;
    const ERROR_NESTED_ELEMENT_NOT_VALID    = 7;


    /**
     * @var string
     */
    protected $log = null;
    /**
     * Last error.
     *
     * @var int
     */
    protected $errorCode = null;

    /**
     * @var string
     */
    protected $errorMessage = null;

    /**
     * @var string
     */
    protected $errorNode = null;

    /**
     * Gets error.
     *
     * @return array
     */
    public function getErrorInfo()
    {
        return [
            'code'  => $this->getErrorCode(),
            'error' => $this->getErrorMessage(),
            'node'  => $this->getErrorMessage(),
        ];
    }


    /**
     * Sets error.
     *
     * @param int    $code
     * @param string $message
     */
    public function setError($code, $node, $message = '')
    {
        $this->errorCode = $code;
        $this->errorNode = $node;
        $this->errorMessage = $message;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorNode()
    {
        return $this->errorNode;
    }

    /**
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param $log
     *
     * @return $this
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }



    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->errorCode === null;
    }
}
