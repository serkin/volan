<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class array_validator extends AbstractValidator
{
    /**
     * @param mixed $nodeData
     *
     * @return bool
     */
    public function isValid($nodeData)
    {
        return is_array($nodeData);
    }
}
