<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

abstract class AbstractValidator
{
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
     *
     * @param mixed $nodeData
     *
     * @return bool
     */
    abstract public function isValid($nodeData);
}
