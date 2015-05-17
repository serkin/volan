<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class required_string_validator extends string_validator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
