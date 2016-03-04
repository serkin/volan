<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

abstract class AbstractValidator
{

    /**
     * @var string Last Error description
     */
    protected $errorDescription = null;

    /**
     * @var array $params
     */
    protected $params = [];

    /**
     * @return bool
     */
    public function isRequired()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isNested()
    {
        return false;
    }

    /**
     * Gets error description
     *
     * @return mixed
     */
    public function getErrorDescription()
    {
        return $this->errorDescription;
    }


    /**
     * Sets error description
     *
     * @param string $error
     *
     */
    public function setErrorDescription($error)
    {
        $this->errorDescription = $error;
    }

    /*
     * Sets custom params
     *
     * @param array $arr
     *
     * @return void
     */
    public function setParams($arr = [])
    {
        foreach ($arr  as $key => $value) {
            $this->params[$key] = $value;
        }
    }


    /**
     *
     * @param mixed $nodeData
     *
     * @return bool
     */
    abstract public function isValid($nodeData);
}
