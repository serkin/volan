<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class required_boolean_validator extends boolean_validator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
